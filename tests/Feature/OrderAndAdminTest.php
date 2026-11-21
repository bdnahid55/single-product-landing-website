<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OrderAndAdminTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed settings
        Setting::set('site_title', 'সুন্দরবনের মধু');
        Setting::set('delivery_inside_dhaka', '80');
        Setting::set('delivery_outside_dhaka', '130');

        // Create Admin
        $this->admin = User::create([
            'name'     => 'Super Admin',
            'email'    => 'admin@modhu.com',
            'password' => Hash::make('password123'),
            'is_admin' => true,
        ]);

        // Seed Spatie Permissions & Roles for testing
        $permissions = [
            'view-dashboard',
            'manage-orders',
            'manage-products',
            'manage-reviews',
            'manage-sections',
            'manage-settings',
            'manage-admins',
            'manage-roles',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::findOrCreate($permission, 'web');
        }

        $superAdminRole = \Spatie\Permission\Models\Role::findOrCreate('Super Admin', 'web');
        $superAdminRole->syncPermissions($permissions);

        $managerRole = \Spatie\Permission\Models\Role::findOrCreate('Manager', 'web');
        $managerRole->syncPermissions([
            'view-dashboard',
            'manage-orders',
            'manage-products',
            'manage-reviews',
            'manage-sections',
        ]);

        \Spatie\Permission\Models\Role::findOrCreate('Editor', 'web');

        $this->admin->assignRole('Super Admin');

        // Create Sample Product
        $this->product = Product::create([
            'name'          => 'খাঁটি সুন্দরবনের মধু (১ কেজি)',
            'slug'          => 'pure-honey-1kg',
            'weight'        => '১ কেজি',
            'regular_price' => 1200.00,
            'sale_price'    => 1050.00,
            'stock'         => 50,
            'is_featured'   => true,
            'is_active'     => true,
        ]);
    }

    public function test_landing_page_renders_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('খাঁটি সুন্দরবনের মধু');
        $response->assertSee('1,050.00');
        $response->assertSee('<textarea name="customer_address" id="customerAddress"', false);
        $response->assertSee('rel="icon"', false);
        $response->assertSee('favicon', false);
    }

    public function test_customer_can_place_order_with_multiline_address_textarea(): void
    {
        $multilineAddress = "বাড়ি #১২, রোড #০৫\nসেক্টর #০৩, উত্তরা\nঢাকা-১২৩০";
        $response = $this->post('/orders', [
            'customer_name'    => 'মোহাম্মদ করিম',
            'customer_phone'   => '01899887766',
            'customer_address' => $multilineAddress,
            'delivery_area'    => 'inside_dhaka',
            'product_id'       => $this->product->id,
            'quantity'         => 1,
        ]);

        $order = Order::where('customer_phone', '01899887766')->first();
        $this->assertNotNull($order);
        $this->assertEquals($multilineAddress, $order->customer_address);
        $response->assertRedirect(route('orders.success', $order->id));
    }

    public function test_customer_can_place_an_order_and_redirects_to_success_page(): void
    {
        $response = $this->post('/orders', [
            'customer_name'    => 'নাজমুল হাসান',
            'customer_phone'   => '01712345678',
            'customer_address' => 'ধানমন্ডি, ঢাকা',
            'delivery_area'    => 'inside_dhaka',
            'product_id'       => $this->product->id,
            'quantity'         => 2,
            'customer_notes'   => 'জরুরি ডেলিভারি প্রয়োজন',
        ]);

        $order = Order::first();
        $this->assertNotNull($order);
        $this->assertEquals('নাজমুল হাসান', $order->customer_name);
        $this->assertEquals(80.00, $order->delivery_charge);
        $this->assertEquals(2100.00, $order->subtotal);
        $this->assertEquals(2180.00, $order->total_amount);
        $this->assertEquals('pending', $order->order_status);
        $this->assertStringStartsWith('ORD-', $order->order_number);

        // Check stock decremented: 50 - 2 = 48
        $this->assertEquals(48, $this->product->fresh()->stock);

        // Check line item
        $item = OrderItem::where('order_id', $order->id)->first();
        $this->assertNotNull($item);
        $this->assertEquals(2, $item->quantity);
        $this->assertEquals(1050.00, $item->unit_price);
        $this->assertEquals(2100.00, $item->subtotal);

        // Assert redirect to order success page
        $response->assertRedirect(route('orders.success', $order->id));

        // Follow redirect to success page
        $successResponse = $this->get(route('orders.success', $order->id));
        $successResponse->assertStatus(200);
        $successResponse->assertSee($order->order_number);
        $successResponse->assertSee('2,180.00');
    }

    public function test_admin_can_login_with_valid_credentials(): void
    {
        $response = $this->post('/admin/login', [
            'email'    => 'admin@modhu.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->admin);
    }

    public function test_unauthenticated_user_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_orders_and_update_status(): void
    {
        $order = Order::create([
            'order_number'    => 'ORD-TEST-1',
            'customer_name'   => 'করিম চৌধুরী',
            'customer_phone'  => '01811223344',
            'customer_address'=> 'চট্টগ্রাম',
            'delivery_area'   => 'outside_dhaka',
            'delivery_charge' => 130.00,
            'subtotal'        => 1050.00,
            'total_amount'    => 1180.00,
            'payment_method'  => 'cod',
            'payment_status'  => 'unpaid',
            'order_status'    => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/orders');
        $response->assertStatus(200);
        $response->assertSee('ORD-TEST-1');
        $response->assertSee('করিম চৌধুরী');

        // Update status to delivered and paid
        $updateResponse = $this->actingAs($this->admin)->patch("/admin/orders/{$order->id}/status", [
            'order_status'   => 'delivered',
            'payment_status' => 'paid',
            'admin_notes'    => 'Steadfast CN: 889922',
        ]);

        $updateResponse->assertSessionHas('success');
        $this->assertEquals('delivered', $order->fresh()->order_status);
        $this->assertEquals('paid', $order->fresh()->payment_status);
        $this->assertEquals('Steadfast CN: 889922', $order->fresh()->admin_notes);
    }

    public function test_admin_can_view_printable_invoice(): void
    {
        $order = Order::create([
            'order_number'    => 'ORD-INV-99',
            'customer_name'   => 'আব্দুল কাদের',
            'customer_phone'  => '01999999999',
            'customer_address'=> 'মিরপুর ১০, ঢাকা',
            'delivery_area'   => 'inside_dhaka',
            'delivery_charge' => 80.00,
            'subtotal'        => 1050.00,
            'total_amount'    => 1130.00,
            'payment_method'  => 'cod',
            'payment_status'  => 'unpaid',
            'order_status'    => 'processing',
        ]);

        OrderItem::create([
            'order_id'     => $order->id,
            'product_id'   => $this->product->id,
            'product_name' => $this->product->name,
            'weight'       => '১ কেজি',
            'unit_price'   => 1050.00,
            'quantity'     => 1,
            'subtotal'     => 1050.00,
        ]);

        $response = $this->actingAs($this->admin)->get("/admin/orders/{$order->id}/invoice");
        $response->assertStatus(200);
        $response->assertSee('ORD-INV-99');
        $response->assertSee('আব্দুল কাদের');
        $response->assertSee('Print Invoice');
        $response->assertDontSee('Order ID:');
        $response->assertSee('alt="Logo"', false);
    }

    public function test_admin_can_create_new_product(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/products', [
            'name'          => 'স্পেশাল সুন্দরবন মধু (২ কেজি)',
            'weight'        => '২ কেজি',
            'regular_price' => 2400.00,
            'sale_price'    => 2000.00,
            'stock'         => 25,
            'badge'         => 'ধামাকা অফার',
            'is_active'     => 1,
            'is_featured'   => 0,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'স্পেশাল সুন্দরবন মধু (২ কেজি)',
            'sale_price' => 2000.00,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_deactivate_product_and_it_disappears_from_website(): void
    {
        // Initially product is active
        $this->assertTrue($this->product->fresh()->is_active);
        $initialHome = $this->get('/');
        $initialHome->assertSee($this->product->name);

        // Deactivate product by submitting is_active = 0 (or unchecking the checkbox)
        $response = $this->actingAs($this->admin)->put("/admin/products/{$this->product->id}", [
            'name'          => $this->product->name,
            'weight'        => $this->product->weight,
            'regular_price' => $this->product->regular_price,
            'sale_price'    => $this->product->sale_price,
            'stock'         => $this->product->stock,
            'is_active'     => 0, // Unchecked
            'is_featured'   => 0,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertFalse((bool) $this->product->fresh()->is_active);

        // Verify it disappears from website
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertDontSee('এই পণ্যটি কিনুন');

        // Test quick 1-click toggle action
        $toggleResponse = $this->actingAs($this->admin)->patch("/admin/products/{$this->product->id}/toggle-status");
        $toggleResponse->assertRedirect();
        $this->assertTrue((bool) $this->product->fresh()->is_active);

        // Re-check website shows product again
        $recheckHome = $this->get('/');
        $recheckHome->assertSee($this->product->name);
    }

    public function test_admin_can_switch_selling_mode_between_single_and_multiple_products(): void
    {
        // Create second product
        $prod2 = Product::create([
            'name'          => 'সুন্দরবনের খলিসা মধু (৫০০ গ্রাম)',
            'slug'          => 'khalisa-500g',
            'weight'        => '৫০০ গ্রাম',
            'regular_price' => 600.00,
            'sale_price'    => 500.00,
            'stock'         => 30,
            'is_active'     => true,
            'is_featured'   => false,
        ]);

        // Default: Multi-Product Mode -> shows package cards and grid
        $multiHome = $this->get('/');
        $multiHome->assertStatus(200);
        $multiHome->assertSee('আপনার পছন্দের মধু প্যাকেজ নির্বাচন করুন');
        $multiHome->assertSee('৫০০ গ্রাম');

        // Switch to Single Product Mode
        $modeResponse = $this->actingAs($this->admin)->put('/admin/products-mode', [
            'store_mode'        => 'single',
            'single_product_id' => $this->product->id,
        ]);

        $modeResponse->assertRedirect();
        $this->assertEquals('single', Setting::get('store_mode'));

        // In Single Product Mode: Only single product is loaded, packages grid is hidden
        $singleHome = $this->get('/');
        $singleHome->assertStatus(200);
        $singleHome->assertSee($this->product->name);
        // The multiple packages selector should NOT be displayed
        $singleHome->assertDontSee('আপনার পছন্দের মধু প্যাকেজ নির্বাচন করুন');

        // Switch back to Multi-Product Mode
        $backResponse = $this->actingAs($this->admin)->put('/admin/products-mode', [
            'store_mode' => 'multiple',
        ]);
        $backResponse->assertRedirect();
        $this->assertEquals('multiple', Setting::get('store_mode'));

        $recheckMulti = $this->get('/');
        $recheckMulti->assertSee('আপনার পছন্দের মধু প্যাকেজ নির্বাচন করুন');
    }

    public function test_admin_can_update_settings(): void
    {
        $response = $this->actingAs($this->admin)->put('/admin/settings', [
            'site_title'             => 'নতুন মধু শপ',
            'delivery_inside_dhaka'  => '90',
            'delivery_outside_dhaka' => '150',
            'whatsapp_number'        => '8801799999999',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('নতুন মধু শপ', Setting::get('site_title'));
        $this->assertEquals('90', Setting::get('delivery_inside_dhaka'));
        $this->assertEquals('150', Setting::get('delivery_outside_dhaka'));
    }

    public function test_admin_can_save_and_sanitize_webmaster_and_analytics_tracking(): void
    {
        $response = $this->actingAs($this->admin)->put('/admin/settings', [
            'google_site_verification' => '<meta name="google-site-verification" content="VERIF_TOKEN_123" />',
            'google_analytics_id'      => 'g-12345abcdef',
            'facebook_pixel_id'        => 'fbq(\'init\', \'987654321012345\');',
        ]);

        $response->assertSessionHas('success');

        // Check values normalized and stored in database
        $this->assertEquals('VERIF_TOKEN_123', Setting::get('google_site_verification'));
        $this->assertEquals('G-12345ABCDEF', Setting::get('google_analytics_id'));
        $this->assertEquals('987654321012345', Setting::get('facebook_pixel_id'));

        // Check group is 'analytics'
        $dbRecord = Setting::where('key', 'google_analytics_id')->first();
        $this->assertEquals('analytics', $dbRecord->group);

        // Check homepage output
        $homeResponse = $this->get('/');
        $homeResponse->assertSee('<meta name="google-site-verification" content="VERIF_TOKEN_123">', false);
        $homeResponse->assertSee('https://www.googletagmanager.com/gtag/js?id=G-12345ABCDEF', false);
        $homeResponse->assertSee('fbq(\'init\', \'987654321012345\')', false);
    }

    public function test_invalid_analytics_tracking_is_rejected(): void
    {
        $response = $this->actingAs($this->admin)->put('/admin/settings', [
            'facebook_pixel_id' => 'not-a-number-pixel-id',
        ]);

        $response->assertSessionHasErrors(['facebook_pixel_id']);
    }

    public function test_customer_can_view_order_tracking_page_and_track_order(): void
    {
        // 1. Visit tracking page
        $pageResponse = $this->get('/track-order');
        $pageResponse->assertStatus(200);
        $pageResponse->assertSee('অর্ডার ট্র্যাকিং (Order Tracking)');
        $pageResponse->assertSee('আপনার অর্ডারের তথ্য খুঁজুন');
        $pageResponse->assertSee('alt="Logo"', false);

        // 2. Track existing order
        $order = Order::create([
            'order_number'     => Order::generateOrderNumber(),
            'customer_name'    => 'কামরুল হাসান',
            'customer_phone'   => '01712345678',
            'customer_address' => 'ধানমন্ডি, ঢাকা',
            'delivery_area'    => 'inside_dhaka',
            'delivery_charge'  => 80,
            'subtotal'         => 1050,
            'total_amount'     => 1130,
            'payment_method'   => 'cod',
            'order_status'     => 'pending',
        ]);
        OrderItem::create([
            'order_id'     => $order->id,
            'product_id'   => $this->product->id,
            'product_name' => $this->product->name,
            'weight'       => $this->product->weight,
            'unit_price'   => $this->product->sale_price,
            'quantity'     => 1,
            'subtotal'     => 1050,
        ]);

        $trackResponse = $this->post('/track-order', [
            'order_number'   => $order->order_number,
            'customer_phone' => $order->customer_phone,
        ]);
        $trackResponse->assertStatus(200);
        $trackResponse->assertSee($order->order_number);
        $trackResponse->assertSee($order->customer_name);
        $trackResponse->assertSee('অর্ডার গৃহীত');

        // 3. Track with invalid details
        $invalidResponse = $this->post('/track-order', [
            'order_number'   => 'ORD-NONEXISTENT',
            'customer_phone' => '01999999999',
        ]);
        $invalidResponse->assertStatus(200);
        $invalidResponse->assertSee('কোনো অর্ডার পাওয়া যায়নি');
    }

    public function test_footer_contains_order_track_link_and_excludes_sitemap_and_admin_login(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('অর্ডার ট্র্যাক করুন');
        $response->assertSee(route('order.track'));
        $response->assertDontSee('>সাইটম্যাপ<', false);
        $response->assertDontSee('>অ্যাডমিন লগইন<', false);
        $response->assertDontSee('<div class="footer-brand"><span>🍯</span>', false);
    }

    public function test_seo_metadata_open_graph_and_sitemap_are_present(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('<meta name="description"', false);
        $response->assertSee('<meta property="og:title"', false);
        $response->assertSee('<meta property="og:image"', false);
        $response->assertSee('schema.org', false);

        // Test Sitemap
        $sitemapResponse = $this->get('/sitemap.xml');
        $sitemapResponse->assertStatus(200);
        $sitemapResponse->assertHeader('Content-Type', 'application/xml');
        $sitemapResponse->assertSee('<urlset', false);
    }

    public function test_admin_can_manage_reviews(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/reviews', [
            'customer_name'     => 'সাইফুল ইসলাম',
            'customer_location' => 'বরিশাল',
            'rating'            => 5,
            'review_text'       => 'খুবই ভালো মধু পেয়েছি।',
            'is_approved'       => 1,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('reviews', [
            'customer_name' => 'সাইফুল ইসলাম',
            'rating'        => 5,
        ]);
    }

    public function test_admin_can_view_and_toggle_section_controls(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/sections');
        $response->assertStatus(200);
        $response->assertSee('ল্যান্ডিং পেজ সেকশন কন্ট্রোল');
        $response->assertSee('section_hero_show');
        $response->assertSee('section_gallery_show');
        $response->assertSee('section_video_show');

        // Test disabling Gallery and Video sections
        $updateResponse = $this->actingAs($this->admin)->put('/admin/sections', [
            'section_hero_show'            => '1',
            'section_problem_show'         => '1',
            'section_why_us_show'          => '1',
            'section_gallery_show'         => '0', // disabled
            'section_products_show'        => '1',
            'section_benefits_show'        => '1',
            'section_video_show'           => '0', // disabled
            'section_reviews_show'         => '1',
            'section_discount_show'        => '1',
            'section_order_form_show'      => '1',
            'section_footer_show'          => '1',
            'section_whatsapp_widget_show' => '1',
        ]);

        $updateResponse->assertRedirect(route('admin.sections.index'));
        $updateResponse->assertSessionHas('success');

        // Verify database settings
        $this->assertEquals('0', Setting::get('section_gallery_show'));
        $this->assertEquals('0', Setting::get('section_video_show'));

        // Verify homepage DOES NOT show the disabled sections
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertDontSee('id="gallery"', false);
        $homeResponse->assertDontSee('id="video"', false);
        // But still shows the enabled sections
        $homeResponse->assertSee('id="hero"', false);
        $homeResponse->assertSee('id="orderForm"', false);

        // Re-enable gallery and verify it returns
        Setting::set('section_gallery_show', '1');
        $recheckHome = $this->get('/');
        $recheckHome->assertSee('id="gallery"', false);
    }

    public function test_customer_can_order_multiple_distinct_products_simultaneously(): void
    {
        $prod2 = Product::create([
            'name'          => 'সুন্দরবনের খলিসা মধু (৫০০ গ্রাম)',
            'slug'          => 'khalisa-500g-multi',
            'weight'        => '৫০০ গ্রাম',
            'regular_price' => 650.00,
            'sale_price'    => 550.00,
            'stock'         => 20,
            'is_active'     => true,
            'is_featured'   => false,
        ]);

        $initialStock1 = $this->product->stock; // 50
        $initialStock2 = $prod2->stock;         // 20

        $postData = [
            'customer_name'     => 'তানভীর আহমেদ',
            'customer_phone'    => '01911223344',
            'customer_address'  => 'রোড ৪, সেক্টর ১০, উত্তরা, ঢাকা',
            'delivery_area'     => 'outside_dhaka', // 130 tk
            'customer_notes'    => 'একত্রে পার্সেল করবেন',
            'selected_products' => [$this->product->id, $prod2->id],
            'quantities'        => [
                $this->product->id => 2, // 1050 * 2 = 2100
                $prod2->id         => 3, // 550 * 3 = 1650
            ],
        ];

        $response = $this->post('/orders', $postData);

        $order = Order::where('customer_phone', '01911223344')->first();
        $this->assertNotNull($order);
        $this->assertEquals(2100.00 + 1650.00, $order->subtotal); // 3750.00
        $this->assertEquals(130.00, $order->delivery_charge);
        $this->assertEquals(3880.00, $order->total_amount);

        // Assert 2 items in order_items
        $this->assertCount(2, $order->items);

        $item1 = $order->items->firstWhere('product_id', $this->product->id);
        $this->assertNotNull($item1);
        $this->assertEquals(2, $item1->quantity);
        $this->assertEquals(1050.00, $item1->unit_price);
        $this->assertEquals(2100.00, $item1->subtotal);

        $item2 = $order->items->firstWhere('product_id', $prod2->id);
        $this->assertNotNull($item2);
        $this->assertEquals(3, $item2->quantity);
        $this->assertEquals(550.00, $item2->unit_price);
        $this->assertEquals(1650.00, $item2->subtotal);

        // Assert both stocks decremented
        $this->assertEquals($initialStock1 - 2, $this->product->fresh()->stock);
        $this->assertEquals($initialStock2 - 3, $prod2->fresh()->stock);

        // Redirects to success page
        $response->assertRedirect(route('orders.success', $order->id));

        // Follow redirect and assert success page shows all ordered products
        $successResponse = $this->get(route('orders.success', $order->id));
        $successResponse->assertStatus(200);
        $successResponse->assertSee($this->product->name);
        $successResponse->assertSee($prod2->name);
        $successResponse->assertSee('3,880.00');
    }

    public function test_multi_order_submission_rejects_empty_selection(): void
    {
        $response = $this->from('/')->post('/orders', [
            'customer_name'     => 'শূন্য পণ্য টেস্ট',
            'customer_phone'    => '01700000000',
            'customer_address'  => 'টেস্ট ঠিকানা',
            'delivery_area'     => 'inside_dhaka',
            'selected_products' => [],
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('orders', [
            'customer_phone' => '01700000000',
        ]);
    }

    public function test_admin_can_toggle_packages_grid_visibility_in_multi_product_mode(): void
    {
        $prod2 = Product::create([
            'name'          => 'সুন্দরবনের খলিসা মধু (৫০০ গ্রাম)',
            'slug'          => 'khalisa-500g-grid-test',
            'weight'        => '৫০০ গ্রাম',
            'regular_price' => 650.00,
            'sale_price'    => 550.00,
            'stock'         => 20,
            'is_active'     => true,
            'is_featured'   => false,
        ]);

        // Default: Multi mode and grid enabled ('all')
        $home1 = $this->get('/');
        $home1->assertStatus(200);
        $home1->assertSee('এই পণ্যটি কিনুন 🛒'); // Button inside the grid
        $home1->assertSee($prod2->name);

        // Turn OFF package grid ('none')
        $updateResp = $this->actingAs($this->admin)->put('/admin/products-mode', [
            'store_mode'         => 'multiple',
            'show_packages_grid' => 'none',
        ]);
        $updateResp->assertRedirect();
        $this->assertEquals('none', Setting::get('show_packages_grid'));

        // Verify homepage DOES NOT show the grid
        $home2 = $this->get('/');
        $home2->assertStatus(200);
        $home2->assertDontSee('এই পণ্যটি কিনুন 🛒'); // Grid button hidden
        // But multi-product cart in checkout is still present
        $home2->assertSee('multi-prod-checkbox', false);
        $home2->assertSee($prod2->name);

        // Turn ON package grid for selected products only
        $updateResp2 = $this->actingAs($this->admin)->put('/admin/products-mode', [
            'store_mode'         => 'multiple',
            'show_packages_grid' => 'selected',
            'grid_product_ids'   => [$this->product->id], // Only prod1
        ]);
        $updateResp2->assertRedirect();
        $this->assertEquals('selected', Setting::get('show_packages_grid'));

        $home3 = $this->get('/');
        $home3->assertStatus(200);
        $home3->assertSee('এই পণ্যটি কিনুন 🛒'); // Grid is shown
        $home3->assertSee($this->product->name);

        // Turn back to 'all'
        $updateResp3 = $this->actingAs($this->admin)->put('/admin/products-mode', [
            'store_mode'         => 'multiple',
            'show_packages_grid' => 'all',
        ]);
        $updateResp3->assertRedirect();
        $this->assertEquals('all', Setting::get('show_packages_grid'));

        $home4 = $this->get('/');
        $home4->assertStatus(200);
        $home4->assertSee('এই পণ্যটি কিনুন 🛒');
    }

    public function test_admin_can_customize_section_background_and_font_colors_excluding_hero(): void
    {
        // Visit Section Controls page
        $viewResp = $this->actingAs($this->admin)->get('/admin/sections');
        $viewResp->assertStatus(200);
        $viewResp->assertSee('section_problem_bg');
        $viewResp->assertSee('section_problem_color');
        $viewResp->assertSee('হিরো ফিক্সড'); // Hero Section excluded indicator

        // Update colors for Problem & Solution and Why Us sections
        $updateResp = $this->actingAs($this->admin)->put('/admin/sections', [
            'section_hero_show'     => '1',
            'section_problem_show'  => '1',
            'section_why_us_show'   => '1',
            'section_problem_bg'    => '#ffeedd',
            'section_problem_color' => '#332211',
            'section_why_us_bg'     => '#112233',
            'section_why_us_color'  => '#ffffff',
        ]);

        $updateResp->assertRedirect(route('admin.sections.index'));
        $updateResp->assertSessionHas('success');

        // Verify settings in database
        $this->assertEquals('#ffeedd', Setting::get('section_problem_bg'));
        $this->assertEquals('#332211', Setting::get('section_problem_color'));
        $this->assertEquals('#112233', Setting::get('section_why_us_bg'));
        $this->assertEquals('#ffffff', Setting::get('section_why_us_color'));

        // Verify homepage renders dynamic styles
        $homeResp = $this->get('/');
        $homeResp->assertStatus(200);
        $homeResp->assertSee('#problemSolution', false);
        $homeResp->assertSee('background-color: #ffeedd !important;', false);
        $homeResp->assertSee('color: #332211 !important;', false);
        $homeResp->assertSee('background-color: #112233 !important;', false);
        $homeResp->assertSee('color: #ffffff !important;', false);
    }

    public function test_admin_can_manage_admin_accounts(): void
    {
        // 1. View admin accounts list
        $listResp = $this->actingAs($this->admin)->get('/admin/users');
        $listResp->assertStatus(200);
        $listResp->assertSee('Admin Accounts Management');
        $listResp->assertSee($this->admin->email);

        // 2. View create form
        $createFormResp = $this->actingAs($this->admin)->get('/admin/users/create');
        $createFormResp->assertStatus(200);
        $createFormResp->assertSee('নতুন অ্যাডমিন তৈরি করুন');

        // 3. Create a new admin
        $storeResp = $this->actingAs($this->admin)->post('/admin/users', [
            'name'                  => 'সহকারী অ্যাডমিন',
            'email'                 => 'assistant@modhu.com',
            'phone'                 => '01755555555',
            'role'                  => 'Manager',
            'password'              => 'secure123',
            'password_confirmation' => 'secure123',
        ]);
        $storeResp->assertRedirect(route('admin.users.index'));
        $storeResp->assertSessionHas('success');

        $newAdmin = User::where('email', 'assistant@modhu.com')->first();
        $this->assertNotNull($newAdmin);
        $this->assertTrue($newAdmin->is_admin);
        $this->assertTrue($newAdmin->hasRole('Manager'));

        // 4. Test that the new admin can log in
        $loginResp = $this->post('/admin/login', [
            'email'    => 'assistant@modhu.com',
            'password' => 'secure123',
        ]);
        $loginResp->assertRedirect(route('admin.dashboard'));

        // 5. Edit the new admin
        $editResp = $this->actingAs($this->admin)->put("/admin/users/{$newAdmin->id}", [
            'name'  => 'সহকারী অ্যাডমিন (আপডেটেড)',
            'email' => 'assistant_updated@modhu.com',
            'phone' => '01766666666',
            'role'  => 'Manager',
        ]);
        $editResp->assertRedirect(route('admin.users.index'));
        $newAdmin->refresh();
        $this->assertEquals('সহকারী অ্যাডমিন (আপডেটেড)', $newAdmin->name);
        $this->assertEquals('assistant_updated@modhu.com', $newAdmin->email);

        // 6. Current admin cannot delete their own account
        $selfDeleteResp = $this->actingAs($this->admin)->delete("/admin/users/{$this->admin->id}");
        $selfDeleteResp->assertSessionHas('error');

        // 7. Delete the newly created admin account
        $deleteResp = $this->actingAs($this->admin)->delete("/admin/users/{$newAdmin->id}");
        $deleteResp->assertRedirect(route('admin.users.index'));
        $deleteResp->assertSessionHas('success');
        $this->assertNull(User::find($newAdmin->id));
    }

    public function test_spatie_role_and_permission_enforcement(): void
    {
        // Create a Manager user who has manage-orders, but NOT manage-settings or manage-admins
        $manager = User::create([
            'name'     => 'Manager User',
            'email'    => 'manager@modhu.com',
            'password' => Hash::make('password123'),
            'is_admin' => true,
        ]);
        $manager->assignRole('Manager');

        // 1. Manager CAN access Orders
        $ordersResp = $this->actingAs($manager)->get('/admin/orders');
        $ordersResp->assertStatus(200);

        // 2. Manager CAN access Products
        $productsResp = $this->actingAs($manager)->get('/admin/products');
        $productsResp->assertStatus(200);

        // 3. Manager CANNOT access Settings (Forbidden 403)
        $settingsResp = $this->actingAs($manager)->get('/admin/settings');
        $settingsResp->assertStatus(403);

        // 4. Manager CANNOT access Admin User Management (Forbidden 403)
        $usersResp = $this->actingAs($manager)->get('/admin/users');
        $usersResp->assertStatus(403);

        // 5. Super Admin CAN access all of them
        $superAdminSettings = $this->actingAs($this->admin)->get('/admin/settings');
        $superAdminSettings->assertStatus(200);

        $superAdminUsers = $this->actingAs($this->admin)->get('/admin/users');
        $superAdminUsers->assertStatus(200);
    }

    public function test_super_admin_can_view_roles_and_create_new_role_with_permissions(): void
    {
        // 1. Roles index accessible
        $indexResp = $this->actingAs($this->admin)->get('/admin/roles');
        $indexResp->assertStatus(200);
        $indexResp->assertSee('Super Admin');
        $indexResp->assertSee('Roles & Permissions');

        // 2. Roles create page accessible
        $createResp = $this->actingAs($this->admin)->get('/admin/roles/create');
        $createResp->assertStatus(200);
        $createResp->assertSee('ড্যাশবোর্ড অ্যাক্সেস');
        $createResp->assertSee('manage-orders');

        // 3. Store new role
        $storeResp = $this->actingAs($this->admin)->post('/admin/roles', [
            'name' => 'Support Staff',
            'permissions' => ['view-dashboard', 'manage-orders'],
        ]);
        $storeResp->assertRedirect('/admin/roles');

        $role = \Spatie\Permission\Models\Role::findByName('Support Staff', 'web');
        $this->assertNotNull($role);
        $this->assertTrue($role->hasPermissionTo('view-dashboard'));
        $this->assertTrue($role->hasPermissionTo('manage-orders'));
        $this->assertFalse($role->hasPermissionTo('manage-settings'));

        // 4. Edit role page
        $editResp = $this->actingAs($this->admin)->get('/admin/roles/' . $role->id . '/edit');
        $editResp->assertStatus(200);
        $editResp->assertSee('Support Staff');

        // 5. Update role permissions
        $updateResp = $this->actingAs($this->admin)->put('/admin/roles/' . $role->id, [
            'name' => 'Support Team Lead',
            'permissions' => ['view-dashboard', 'manage-orders', 'manage-reviews'],
        ]);
        $updateResp->assertRedirect('/admin/roles');

        $role->refresh();
        $this->assertEquals('Support Team Lead', $role->name);
        $this->assertTrue($role->hasPermissionTo('manage-reviews'));

        // 6. Delete role
        $deleteResp = $this->actingAs($this->admin)->delete('/admin/roles/' . $role->id);
        $deleteResp->assertRedirect('/admin/roles');
        $this->assertNull(\Spatie\Permission\Models\Role::where('name', 'Support Team Lead')->first());
    }

    public function test_super_admin_cannot_be_deleted(): void
    {
        $superAdminRole = \Spatie\Permission\Models\Role::findByName('Super Admin', 'web');
        $response = $this->actingAs($this->admin)->delete('/admin/roles/' . $superAdminRole->id);
        $response->assertRedirect('/admin/roles');
        $response->assertSessionHas('error');
        $this->assertNotNull(\Spatie\Permission\Models\Role::findByName('Super Admin', 'web'));
    }

    public function test_cannot_delete_role_if_users_are_assigned(): void
    {
        $role = \Spatie\Permission\Models\Role::create(['name' => 'Field Agent', 'guard_name' => 'web']);
        $user = User::create([
            'name'     => 'Agent 1',
            'email'    => 'agent1@modhu.com',
            'password' => Hash::make('password123'),
            'is_admin' => true,
        ]);
        $user->assignRole('Field Agent');

        $response = $this->actingAs($this->admin)->delete('/admin/roles/' . $role->id);
        $response->assertRedirect('/admin/roles');
        $response->assertSessionHas('error');
        $this->assertNotNull(\Spatie\Permission\Models\Role::where('name', 'Field Agent')->first());
    }

    public function test_unauthorized_user_cannot_access_roles_management(): void
    {
        $manager = User::create([
            'name'     => 'Manager User',
            'email'    => 'manager2@modhu.com',
            'password' => Hash::make('password123'),
            'is_admin' => true,
        ]);
        $manager->assignRole('Manager');

        $response = $this->actingAs($manager)->get('/admin/roles');
        $response->assertStatus(403);
    }

    public function test_editing_roles_and_permissions_dynamically_applies_to_new_and_existing_admins(): void
    {
        // 1. Create a custom role 'Support Agent' with permissions: view-dashboard, manage-orders
        $createRoleResp = $this->actingAs($this->admin)->post('/admin/roles', [
            'name' => 'Support Agent',
            'permissions' => ['view-dashboard', 'manage-orders'],
        ]);
        $createRoleResp->assertRedirect('/admin/roles');

        $role = \Spatie\Permission\Models\Role::findByName('Support Agent', 'web');
        $this->assertNotNull($role);

        // 2. Create a new Admin with this custom role
        $createAdminResp = $this->actingAs($this->admin)->post('/admin/users', [
            'name' => 'Support Person',
            'email' => 'support@modhu.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'role' => 'Support Agent',
        ]);
        $createAdminResp->assertRedirect('/admin/users');

        $newAdmin = User::where('email', 'support@modhu.com')->first();
        $this->assertNotNull($newAdmin);
        $this->assertTrue($newAdmin->hasRole('Support Agent'));

        // 3. Verify permissions immediately active for this new admin
        $this->actingAs($newAdmin)->get('/admin/orders')->assertStatus(200);
        $this->actingAs($newAdmin)->get('/admin/products')->assertStatus(403);
        $this->actingAs($newAdmin)->get('/admin/settings')->assertStatus(403);

        // 4. Edit the role: grant 'manage-products' and revoke 'manage-orders'
        $updateRoleResp = $this->actingAs($this->admin)->put('/admin/roles/' . $role->id, [
            'name' => 'Support Agent',
            'permissions' => ['view-dashboard', 'manage-products'],
        ]);
        $updateRoleResp->assertRedirect('/admin/roles');

        // 5. Verify the EXISTING admin immediately reflects the updated permissions
        $this->actingAs($newAdmin)->get('/admin/products')->assertStatus(200);
        $this->actingAs($newAdmin)->get('/admin/orders')->assertStatus(403);

        // 6. Change this admin's role to 'Manager'
        $updateUserResp = $this->actingAs($this->admin)->put('/admin/users/' . $newAdmin->id, [
            'name' => 'Support Person Promoted',
            'email' => 'support@modhu.com',
            'role' => 'Manager',
        ]);
        $updateUserResp->assertRedirect('/admin/users');

        $newAdmin->refresh();
        $this->assertTrue($newAdmin->hasRole('Manager'));
        $this->assertFalse($newAdmin->hasRole('Support Agent'));

        // Manager has manage-orders, manage-products, manage-reviews, manage-sections
        $this->actingAs($newAdmin)->get('/admin/orders')->assertStatus(200);
        $this->actingAs($newAdmin)->get('/admin/sections')->assertStatus(200);
        $this->actingAs($newAdmin)->get('/admin/settings')->assertStatus(403);
    }
}

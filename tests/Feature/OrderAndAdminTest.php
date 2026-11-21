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
        ]);
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
}

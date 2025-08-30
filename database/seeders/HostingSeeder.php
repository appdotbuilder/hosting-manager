<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Service;
use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class HostingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create service types
        $hostingBasic = ServiceType::create([
            'name' => 'Basic Hosting',
            'slug' => 'basic-hosting',
            'description' => 'Perfect for small websites and blogs',
            'type' => 'hosting',
            'price' => 9.99,
            'billing_cycle' => 'monthly',
            'features' => [
                'storage' => '5GB SSD',
                'bandwidth' => '50GB',
                'email_accounts' => 10,
                'databases' => 2,
                'ssl_included' => true,
            ],
            'active' => true,
        ]);

        $hostingPro = ServiceType::create([
            'name' => 'Professional Hosting',
            'slug' => 'professional-hosting',
            'description' => 'Ideal for business websites and e-commerce',
            'type' => 'hosting',
            'price' => 19.99,
            'billing_cycle' => 'monthly',
            'features' => [
                'storage' => '25GB SSD',
                'bandwidth' => 'Unlimited',
                'email_accounts' => 50,
                'databases' => 10,
                'ssl_included' => true,
                'backup_included' => true,
            ],
            'active' => true,
        ]);

        $domainCom = ServiceType::create([
            'name' => '.com Domain',
            'slug' => 'com-domain',
            'description' => 'Register your .com domain',
            'type' => 'domain',
            'price' => 12.99,
            'billing_cycle' => 'yearly',
            'features' => [
                'dns_management' => true,
                'domain_forwarding' => true,
                'privacy_protection' => true,
            ],
            'active' => true,
        ]);

        $sslBasic = ServiceType::create([
            'name' => 'SSL Certificate',
            'slug' => 'ssl-certificate',
            'description' => 'Secure your website with SSL encryption',
            'type' => 'ssl',
            'price' => 29.99,
            'billing_cycle' => 'yearly',
            'features' => [
                'encryption' => '256-bit',
                'warranty' => '$10,000',
                'browser_compatibility' => '99.9%',
            ],
            'active' => true,
        ]);

        // Create customers
        $customers = Customer::factory()
            ->count(20)
            ->create();

        // Create orders and services for customers
        $customers->each(function ($customer) use ($hostingBasic, $hostingPro, $domainCom, $sslBasic) {
            // Create 1-3 orders per customer
            $orderCount = random_int(1, 3);
            
            for ($i = 0; $i < $orderCount; $i++) {
                $serviceTypes = [$hostingBasic, $hostingPro, $domainCom, $sslBasic];
                $selectedServiceType = $serviceTypes[array_rand($serviceTypes)];
                
                $order = Order::factory()
                    ->for($customer)
                    ->create([
                        'items' => [
                            [
                                'service_type_id' => $selectedServiceType->id,
                                'name' => $selectedServiceType->name,
                                'quantity' => 1,
                                'price' => $selectedServiceType->price,
                                'total' => $selectedServiceType->price,
                            ],
                        ],
                        'subtotal' => $selectedServiceType->price,
                        'tax_amount' => $selectedServiceType->price * 0.1,
                        'total' => $selectedServiceType->price * 1.1,
                    ]);

                // Create service for the order
                $service = Service::factory()
                    ->for($customer)
                    ->for($selectedServiceType, 'serviceType')
                    ->create([
                        'status' => $order->status === 'paid' ? 'active' : 'pending',
                    ]);

                // Create invoice for the order
                Invoice::factory()
                    ->for($customer)
                    ->for($order)
                    ->create([
                        'line_items' => [
                            [
                                'description' => $selectedServiceType->name,
                                'quantity' => 1,
                                'price' => $selectedServiceType->price,
                                'total' => $selectedServiceType->price,
                            ],
                        ],
                        'amount' => $selectedServiceType->price,
                        'tax_amount' => $selectedServiceType->price * 0.1,
                        'total' => $selectedServiceType->price * 1.1,
                        'status' => $order->status === 'paid' ? 'paid' : 'pending',
                    ]);
            }

            // Create some recurring invoices
            if (random_int(1, 100) <= 60) { // 60% chance
                Invoice::factory()
                    ->for($customer)
                    ->recurring()
                    ->create();
            }
        });
    }
}
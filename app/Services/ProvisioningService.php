<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Service;
use App\Models\ServiceType;

class ProvisioningService
{
    /**
     * Provision services for a paid order.
     */
    public function provisionOrder(Order $order): array
    {
        $results = [
            'provisioned' => 0,
            'errors' => []
        ];

        foreach ($order->items as $item) {
            try {
                $serviceType = ServiceType::find($item['service_type_id']);
                $service = $this->provisionService($order->customer_id, $serviceType, $item);
                $results['provisioned']++;
            } catch (\Exception $e) {
                $results['errors'][] = [
                    'service_type_id' => $item['service_type_id'],
                    'error' => $e->getMessage()
                ];
            }
        }

        return $results;
    }

    /**
     * Provision a single service.
     */
    public function provisionService(int $customerId, ServiceType $serviceType, array $orderItem): Service
    {
        // Calculate next billing date based on billing cycle
        $nextBillingDate = match ($serviceType->billing_cycle) {
            'monthly' => now()->addMonth(),
            'quarterly' => now()->addMonths(3),
            'yearly' => now()->addYear(),
            default => now()->addMonth(),
        };

        $provisioningData = $this->generateProvisioningData($serviceType, $orderItem);

        $service = Service::create([
            'customer_id' => $customerId,
            'service_type_id' => $serviceType->id,
            'domain_name' => $orderItem['domain_name'] ?? null,
            'status' => 'active',
            'next_billing_date' => $nextBillingDate,
            'expiry_date' => $nextBillingDate,
            'provisioning_data' => $provisioningData,
            'configuration' => $this->generateConfiguration($serviceType),
        ]);

        // Here you would typically integrate with actual hosting panels
        // like cPanel, Plesk, or domain registrars
        $this->executeProvisioning($service, $serviceType);

        return $service;
    }

    /**
     * Generate provisioning data based on service type.
     */
    public function generateProvisioningData(ServiceType $serviceType, array $orderItem): array
    {
        $data = [
            'provisioned_at' => now(),
            'server_id' => 'SRV-' . uniqid(),
        ];

        switch ($serviceType->type) {
            case 'hosting':
                $data = array_merge($data, [
                    'account_username' => 'user' . random_int(1000, 9999),
                    'account_password' => bin2hex(random_bytes(8)),
                    'control_panel_url' => 'https://cp.hostingprovider.com',
                    'ftp_host' => 'ftp.hostingprovider.com',
                    'nameservers' => [
                        'ns1.hostingprovider.com',
                        'ns2.hostingprovider.com'
                    ]
                ]);
                break;

            case 'domain':
                $data = array_merge($data, [
                    'registrar' => 'NameCheap',
                    'nameservers' => [
                        'dns1.registrar.com',
                        'dns2.registrar.com'
                    ],
                    'registration_period' => 1, // years
                    'auto_renewal' => true
                ]);
                break;

            case 'ssl':
                $data = array_merge($data, [
                    'certificate_authority' => 'Let\'s Encrypt',
                    'certificate_type' => 'DV',
                    'validation_method' => 'HTTP',
                    'certificate_status' => 'issued'
                ]);
                break;

            case 'email':
                $data = array_merge($data, [
                    'mail_server' => 'mail.hostingprovider.com',
                    'imap_port' => 993,
                    'smtp_port' => 587,
                    'webmail_url' => 'https://webmail.hostingprovider.com'
                ]);
                break;
        }

        return $data;
    }

    /**
     * Generate service configuration.
     */
    public function generateConfiguration(ServiceType $serviceType): array
    {
        return [
            'server_location' => 'US-East',
            'php_version' => '8.2',
            'mysql_version' => '8.0',
            'created_at' => now()->toISOString(),
            'features' => $serviceType->features ?? []
        ];
    }

    /**
     * Execute actual provisioning (integrate with hosting panels).
     */
    public function executeProvisioning(Service $service, ServiceType $serviceType): void
    {
        // In a real implementation, this would make API calls to:
        // - cPanel/WHM for hosting accounts
        // - Domain registrar APIs for domain registration
        // - SSL certificate authorities
        // - Email server configuration

        // For now, we'll just log the provisioning
        logger()->info('Service provisioned', [
            'service_id' => $service->id,
            'service_type' => $serviceType->type,
            'customer_id' => $service->customer_id,
            'domain_name' => $service->domain_name,
        ]);

        // Simulate some provisioning delay
        // In production, this would be handled by queued jobs
        sleep(1);
    }

    /**
     * Suspend a service.
     */
    public function suspendService(Service $service): bool
    {
        $service->update(['status' => 'suspended']);

        // Here you would typically:
        // - Suspend the hosting account
        // - Block domain resolution
        // - Disable email accounts
        
        logger()->info('Service suspended', [
            'service_id' => $service->id,
            'customer_id' => $service->customer_id,
        ]);

        return true;
    }

    /**
     * Reactivate a suspended service.
     */
    public function reactivateService(Service $service): bool
    {
        $service->update(['status' => 'active']);

        // Here you would typically:
        // - Reactivate the hosting account
        // - Restore domain resolution
        // - Re-enable email accounts
        
        logger()->info('Service reactivated', [
            'service_id' => $service->id,
            'customer_id' => $service->customer_id,
        ]);

        return true;
    }
}
import AppLayout from '@/components/app-layout';
import { Button } from '@/components/ui/button';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Customers', href: '/customers' },
];

interface Customer {
    id: number;
    name: string;
    email: string;
    company: string | null;
    status: string;
    created_at: string;
    services_count?: number;
    orders_count?: number;
}

interface CustomersIndexProps {
    customers: {
        data: Customer[];
        links: Array<{
            url: string | null;
            label: string;
            active: boolean;
        }>;
    };
    [key: string]: unknown;
}

export default function CustomersIndex({ customers }: CustomersIndexProps) {
    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
        });
    };

    const getStatusColor = (status: string) => {
        switch (status) {
            case 'active':
                return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
            case 'suspended':
                return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
            case 'inactive':
                return 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
            default:
                return 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Customers" />
            <div className="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold">👥 Customers</h1>
                        <p className="text-muted-foreground">Manage your hosting customers</p>
                    </div>
                    <Link href={route('customers.create')}>
                        <Button>Add Customer</Button>
                    </Link>
                </div>

                <div className="bg-card rounded-lg border shadow-sm overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="w-full">
                            <thead className="bg-muted/50">
                                <tr>
                                    <th className="text-left p-4 font-medium">Customer</th>
                                    <th className="text-left p-4 font-medium">Company</th>
                                    <th className="text-left p-4 font-medium">Status</th>
                                    <th className="text-left p-4 font-medium">Services</th>
                                    <th className="text-left p-4 font-medium">Joined</th>
                                    <th className="text-left p-4 font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y">
                                {customers.data.map((customer) => (
                                    <tr key={customer.id} className="hover:bg-muted/50">
                                        <td className="p-4">
                                            <div>
                                                <p className="font-medium">{customer.name}</p>
                                                <p className="text-sm text-muted-foreground">{customer.email}</p>
                                            </div>
                                        </td>
                                        <td className="p-4">
                                            <p className="text-sm">{customer.company || 'N/A'}</p>
                                        </td>
                                        <td className="p-4">
                                            <span className={`px-2 py-1 text-xs rounded-full ${getStatusColor(customer.status)}`}>
                                                {customer.status}
                                            </span>
                                        </td>
                                        <td className="p-4">
                                            <p className="text-sm">{customer.services_count || 0} services</p>
                                        </td>
                                        <td className="p-4">
                                            <p className="text-sm">{formatDate(customer.created_at)}</p>
                                        </td>
                                        <td className="p-4">
                                            <div className="flex items-center gap-2">
                                                <Link
                                                    href={route('customers.show', customer.id)}
                                                    className="text-sm text-blue-600 hover:underline"
                                                >
                                                    View
                                                </Link>
                                                <Link
                                                    href={route('customers.edit', customer.id)}
                                                    className="text-sm text-blue-600 hover:underline"
                                                >
                                                    Edit
                                                </Link>
                                            </div>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>

                    {customers.data.length === 0 && (
                        <div className="text-center py-12">
                            <p className="text-muted-foreground">No customers found</p>
                            <Link href={route('customers.create')} className="mt-4 inline-block">
                                <Button>Add your first customer</Button>
                            </Link>
                        </div>
                    )}
                </div>

                {/* Pagination */}
                {customers.links.length > 3 && (
                    <div className="flex items-center justify-center gap-2">
                        {customers.links.map((link, index) => (
                            <Link
                                key={index}
                                href={link.url || '#'}
                                className={`px-3 py-2 text-sm rounded ${
                                    link.active
                                        ? 'bg-blue-600 text-white'
                                        : link.url
                                        ? 'bg-card border hover:bg-muted text-foreground'
                                        : 'bg-muted text-muted-foreground cursor-not-allowed'
                                }`}
                                dangerouslySetInnerHTML={{ __html: link.label }}
                            />
                        ))}
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
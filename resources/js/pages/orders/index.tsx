import AppLayout from '@/components/app-layout';
import { Button } from '@/components/ui/button';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Orders', href: '/orders' },
];

interface Order {
    id: number;
    order_number: string;
    total: number;
    status: string;
    created_at: string;
    customer: {
        id: number;
        name: string;
        email: string;
    };
}

interface OrdersIndexProps {
    orders: {
        data: Order[];
        links: Array<{
            url: string | null;
            label: string;
            active: boolean;
        }>;
    };
    [key: string]: unknown;
}

export default function OrdersIndex({ orders }: OrdersIndexProps) {
    const formatCurrency = (amount: number) => {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
        }).format(amount);
    };

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
        });
    };

    const getStatusColor = (status: string) => {
        switch (status) {
            case 'paid':
                return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
            case 'pending':
                return 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200';
            case 'cancelled':
                return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
            case 'refunded':
                return 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
            default:
                return 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Orders" />
            <div className="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold">📋 Orders</h1>
                        <p className="text-muted-foreground">Manage customer orders and payments</p>
                    </div>
                    <Link href={route('orders.create')}>
                        <Button>Create Order</Button>
                    </Link>
                </div>

                <div className="bg-card rounded-lg border shadow-sm overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="w-full">
                            <thead className="bg-muted/50">
                                <tr>
                                    <th className="text-left p-4 font-medium">Order #</th>
                                    <th className="text-left p-4 font-medium">Customer</th>
                                    <th className="text-left p-4 font-medium">Amount</th>
                                    <th className="text-left p-4 font-medium">Status</th>
                                    <th className="text-left p-4 font-medium">Date</th>
                                    <th className="text-left p-4 font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y">
                                {orders.data.map((order) => (
                                    <tr key={order.id} className="hover:bg-muted/50">
                                        <td className="p-4">
                                            <p className="font-medium">{order.order_number}</p>
                                        </td>
                                        <td className="p-4">
                                            <div>
                                                <p className="font-medium">{order.customer.name}</p>
                                                <p className="text-sm text-muted-foreground">{order.customer.email}</p>
                                            </div>
                                        </td>
                                        <td className="p-4">
                                            <p className="font-medium">{formatCurrency(order.total)}</p>
                                        </td>
                                        <td className="p-4">
                                            <span className={`px-2 py-1 text-xs rounded-full ${getStatusColor(order.status)}`}>
                                                {order.status}
                                            </span>
                                        </td>
                                        <td className="p-4">
                                            <p className="text-sm">{formatDate(order.created_at)}</p>
                                        </td>
                                        <td className="p-4">
                                            <div className="flex items-center gap-2">
                                                <Link
                                                    href={route('orders.show', order.id)}
                                                    className="text-sm text-blue-600 hover:underline"
                                                >
                                                    View
                                                </Link>
                                                {order.status === 'pending' && (
                                                    <form
                                                        onSubmit={(e) => {
                                                            e.preventDefault();
                                                            router.patch(route('orders.update', order.id), {
                                                                status: 'paid'
                                                            });
                                                        }}
                                                        className="inline"
                                                    >
                                                        <button
                                                            type="submit"
                                                            className="text-sm text-green-600 hover:underline"
                                                        >
                                                            Mark Paid
                                                        </button>
                                                    </form>
                                                )}
                                            </div>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>

                    {orders.data.length === 0 && (
                        <div className="text-center py-12">
                            <p className="text-muted-foreground">No orders found</p>
                            <Link href={route('orders.create')} className="mt-4 inline-block">
                                <Button>Create your first order</Button>
                            </Link>
                        </div>
                    )}
                </div>

                {/* Pagination */}
                {orders.links.length > 3 && (
                    <div className="flex items-center justify-center gap-2">
                        {orders.links.map((link, index) => (
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
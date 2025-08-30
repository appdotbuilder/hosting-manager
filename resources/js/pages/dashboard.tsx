import AppLayout from '@/components/app-layout';
import { type BreadcrumbItem, type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

interface DashboardProps {
    stats: {
        total_customers: number;
        active_services: number;
        pending_orders: number;
        unpaid_invoices: number;
        monthly_revenue: number;
        overdue_invoices: number;
    };
    recentOrders: Array<{
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
    }>;
    recentInvoices: Array<{
        id: number;
        invoice_number: string;
        total: number;
        due_date: string;
        customer: {
            id: number;
            name: string;
            email: string;
        };
    }>;
    upcomingBilling: Array<{
        id: number;
        domain_name: string;
        next_billing_date: string;
        customer: {
            id: number;
            name: string;
        };
        service_type: {
            name: string;
            price: number;
        };
    }>;
    [key: string]: unknown;
}

export default function Dashboard({ stats, recentOrders, recentInvoices, upcomingBilling }: DashboardProps) {
    const { auth } = usePage<SharedData>().props;

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

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-6 rounded-xl p-6 overflow-x-auto">
                <div>
                    <h1 className="text-2xl font-bold">🏢 Hosting Management Dashboard</h1>
                    <p className="text-muted-foreground">Welcome back, {auth.user?.name}! Here's your business overview.</p>
                </div>

                {/* Stats Grid */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div className="bg-card p-6 rounded-lg border shadow-sm">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-muted-foreground">Total Customers</p>
                                <p className="text-2xl font-bold">{stats.total_customers}</p>
                            </div>
                            <div className="h-8 w-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                <span className="text-blue-600 dark:text-blue-400">👥</span>
                            </div>
                        </div>
                    </div>

                    <div className="bg-card p-6 rounded-lg border shadow-sm">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-muted-foreground">Active Services</p>
                                <p className="text-2xl font-bold text-green-600">{stats.active_services}</p>
                            </div>
                            <div className="h-8 w-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                <span className="text-green-600 dark:text-green-400">⚡</span>
                            </div>
                        </div>
                    </div>

                    <div className="bg-card p-6 rounded-lg border shadow-sm">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-muted-foreground">Monthly Revenue</p>
                                <p className="text-2xl font-bold text-green-600">{formatCurrency(stats.monthly_revenue)}</p>
                            </div>
                            <div className="h-8 w-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                <span className="text-green-600 dark:text-green-400">💰</span>
                            </div>
                        </div>
                    </div>

                    <div className="bg-card p-6 rounded-lg border shadow-sm">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-muted-foreground">Pending Orders</p>
                                <p className="text-2xl font-bold text-orange-600">{stats.pending_orders}</p>
                            </div>
                            <div className="h-8 w-8 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
                                <span className="text-orange-600 dark:text-orange-400">📋</span>
                            </div>
                        </div>
                    </div>

                    <div className="bg-card p-6 rounded-lg border shadow-sm">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-muted-foreground">Unpaid Invoices</p>
                                <p className="text-2xl font-bold text-red-600">{stats.unpaid_invoices}</p>
                            </div>
                            <div className="h-8 w-8 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                                <span className="text-red-600 dark:text-red-400">💳</span>
                            </div>
                        </div>
                    </div>

                    <div className="bg-card p-6 rounded-lg border shadow-sm">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-muted-foreground">Overdue Invoices</p>
                                <p className="text-2xl font-bold text-red-600">{stats.overdue_invoices}</p>
                            </div>
                            <div className="h-8 w-8 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                                <span className="text-red-600 dark:text-red-400">⚠️</span>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Quick Actions */}
                <div className="bg-card p-6 rounded-lg border shadow-sm">
                    <h2 className="text-lg font-semibold mb-4">Quick Actions</h2>
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <Link
                            href={route('customers.create')}
                            className="flex items-center p-4 bg-blue-50 dark:bg-blue-950 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900 transition-colors"
                        >
                            <span className="text-blue-600 dark:text-blue-400 mr-3">👤</span>
                            <span className="font-medium">Add Customer</span>
                        </Link>
                        <Link
                            href={route('orders.create')}
                            className="flex items-center p-4 bg-green-50 dark:bg-green-950 rounded-lg hover:bg-green-100 dark:hover:bg-green-900 transition-colors"
                        >
                            <span className="text-green-600 dark:text-green-400 mr-3">📝</span>
                            <span className="font-medium">Create Order</span>
                        </Link>
                        <Link
                            href={route('invoices.index')}
                            className="flex items-center p-4 bg-orange-50 dark:bg-orange-950 rounded-lg hover:bg-orange-100 dark:hover:bg-orange-900 transition-colors"
                        >
                            <span className="text-orange-600 dark:text-orange-400 mr-3">📄</span>
                            <span className="font-medium">View Invoices</span>
                        </Link>
                        <Link
                            href={route('services.index')}
                            className="flex items-center p-4 bg-purple-50 dark:bg-purple-950 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900 transition-colors"
                        >
                            <span className="text-purple-600 dark:text-purple-400 mr-3">⚙️</span>
                            <span className="font-medium">Manage Services</span>
                        </Link>
                    </div>
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {/* Recent Orders */}
                    <div className="bg-card p-6 rounded-lg border shadow-sm">
                        <div className="flex items-center justify-between mb-4">
                            <h2 className="text-lg font-semibold">Recent Orders</h2>
                            <Link
                                href={route('orders.index')}
                                className="text-sm text-blue-600 dark:text-blue-400 hover:underline"
                            >
                                View all
                            </Link>
                        </div>
                        <div className="space-y-3">
                            {recentOrders.length > 0 ? (
                                recentOrders.map((order) => (
                                    <div key={order.id} className="flex items-center justify-between p-3 bg-muted/50 rounded-lg">
                                        <div>
                                            <p className="font-medium text-sm">{order.order_number}</p>
                                            <p className="text-xs text-muted-foreground">{order.customer.name}</p>
                                        </div>
                                        <div className="text-right">
                                            <p className="font-medium text-sm">{formatCurrency(order.total)}</p>
                                            <p className={`text-xs px-2 py-1 rounded-full ${
                                                order.status === 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                                order.status === 'pending' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' :
                                                'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
                                            }`}>
                                                {order.status}
                                            </p>
                                        </div>
                                    </div>
                                ))
                            ) : (
                                <p className="text-muted-foreground text-sm">No recent orders</p>
                            )}
                        </div>
                    </div>

                    {/* Unpaid Invoices */}
                    <div className="bg-card p-6 rounded-lg border shadow-sm">
                        <div className="flex items-center justify-between mb-4">
                            <h2 className="text-lg font-semibold">Unpaid Invoices</h2>
                            <Link
                                href={route('invoices.index')}
                                className="text-sm text-blue-600 dark:text-blue-400 hover:underline"
                            >
                                View all
                            </Link>
                        </div>
                        <div className="space-y-3">
                            {recentInvoices.length > 0 ? (
                                recentInvoices.map((invoice) => (
                                    <div key={invoice.id} className="flex items-center justify-between p-3 bg-red-50 dark:bg-red-950 rounded-lg">
                                        <div>
                                            <p className="font-medium text-sm">{invoice.invoice_number}</p>
                                            <p className="text-xs text-muted-foreground">{invoice.customer.name}</p>
                                        </div>
                                        <div className="text-right">
                                            <p className="font-medium text-sm">{formatCurrency(invoice.total)}</p>
                                            <p className="text-xs text-red-600 dark:text-red-400">
                                                Due {formatDate(invoice.due_date)}
                                            </p>
                                        </div>
                                    </div>
                                ))
                            ) : (
                                <p className="text-muted-foreground text-sm">No unpaid invoices</p>
                            )}
                        </div>
                    </div>
                </div>

                {/* Upcoming Billing */}
                {upcomingBilling.length > 0 && (
                    <div className="bg-card p-6 rounded-lg border shadow-sm">
                        <h2 className="text-lg font-semibold mb-4">Upcoming Billing (Next 7 Days)</h2>
                        <div className="space-y-3">
                            {upcomingBilling.map((service) => (
                                <div key={service.id} className="flex items-center justify-between p-3 bg-yellow-50 dark:bg-yellow-950 rounded-lg">
                                    <div>
                                        <p className="font-medium text-sm">
                                            {service.service_type.name}
                                            {service.domain_name && ` - ${service.domain_name}`}
                                        </p>
                                        <p className="text-xs text-muted-foreground">{service.customer.name}</p>
                                    </div>
                                    <div className="text-right">
                                        <p className="font-medium text-sm">{formatCurrency(service.service_type.price)}</p>
                                        <p className="text-xs text-yellow-600 dark:text-yellow-400">
                                            Due {formatDate(service.next_billing_date)}
                                        </p>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
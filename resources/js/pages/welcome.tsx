import { type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';

export default function Welcome() {
    const { auth } = usePage<SharedData>().props;

    return (
        <>
            <Head title="HostingHub - Complete Hosting Management Solution">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
            </Head>
            <div className="flex min-h-screen flex-col bg-gradient-to-br from-blue-50 via-white to-purple-50 text-gray-900 dark:from-gray-900 dark:via-gray-800 dark:to-purple-900 dark:text-gray-100">
                <header className="w-full px-6 py-4">
                    <nav className="flex items-center justify-between max-w-7xl mx-auto">
                        <div className="flex items-center space-x-2">
                            <div className="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <span className="text-white font-bold text-lg">🏢</span>
                            </div>
                            <span className="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                HostingHub
                            </span>
                        </div>
                        <div className="flex items-center gap-4">
                            {auth.user ? (
                                <Link
                                    href={route('dashboard')}
                                    className="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl"
                                >
                                    Go to Dashboard
                                </Link>
                            ) : (
                                <>
                                    <Link
                                        href={route('login')}
                                        className="inline-flex items-center px-4 py-2 text-gray-700 hover:text-blue-600 font-medium transition-colors dark:text-gray-300 dark:hover:text-blue-400"
                                    >
                                        Sign In
                                    </Link>
                                    <Link
                                        href={route('register')}
                                        className="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl"
                                    >
                                        Get Started
                                    </Link>
                                </>
                            )}
                        </div>
                    </nav>
                </header>

                <main className="flex-1 px-6">
                    <div className="max-w-7xl mx-auto">
                        {/* Hero Section */}
                        <div className="text-center py-20">
                            <h1 className="text-5xl md:text-6xl font-bold mb-6">
                                <span className="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                    Complete Hosting
                                </span>
                                <br />
                                <span className="text-gray-900 dark:text-gray-100">
                                    Management Solution
                                </span>
                            </h1>
                            <p className="text-xl text-gray-600 dark:text-gray-300 mb-8 max-w-3xl mx-auto leading-relaxed">
                                🚀 Streamline your hosting business with automated billing, service provisioning, 
                                and comprehensive customer management. Everything you need to run a successful hosting company.
                            </p>
                            {!auth.user && (
                                <div className="flex flex-col sm:flex-row gap-4 justify-center">
                                    <Link
                                        href={route('register')}
                                        className="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-xl hover:shadow-2xl transform hover:-translate-y-1"
                                    >
                                        Start Free Trial 🎯
                                    </Link>
                                    <Link
                                        href={route('login')}
                                        className="inline-flex items-center justify-center px-8 py-4 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:border-blue-500 hover:text-blue-600 dark:hover:border-blue-400 dark:hover:text-blue-400 transition-all duration-200"
                                    >
                                        View Demo 👁️
                                    </Link>
                                </div>
                            )}
                        </div>

                        {/* Features Grid */}
                        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8 py-16">
                            <div className="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-200 border border-gray-100 dark:border-gray-700">
                                <div className="w-12 h-12 bg-gradient-to-r from-green-400 to-blue-500 rounded-xl flex items-center justify-center mb-6">
                                    <span className="text-2xl">👥</span>
                                </div>
                                <h3 className="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Customer Management</h3>
                                <p className="text-gray-600 dark:text-gray-300 leading-relaxed">
                                    Complete customer database with service history, billing records, and automated communication tools.
                                </p>
                            </div>

                            <div className="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-200 border border-gray-100 dark:border-gray-700">
                                <div className="w-12 h-12 bg-gradient-to-r from-purple-400 to-pink-500 rounded-xl flex items-center justify-center mb-6">
                                    <span className="text-2xl">💰</span>
                                </div>
                                <h3 className="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Automated Billing</h3>
                                <p className="text-gray-600 dark:text-gray-300 leading-relaxed">
                                    Recurring invoices, payment processing, and automated dunning sequences to maximize revenue collection.
                                </p>
                            </div>

                            <div className="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-200 border border-gray-100 dark:border-gray-700">
                                <div className="w-12 h-12 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center mb-6">
                                    <span className="text-2xl">⚡</span>
                                </div>
                                <h3 className="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Service Provisioning</h3>
                                <p className="text-gray-600 dark:text-gray-300 leading-relaxed">
                                    Automatic service activation upon payment completion. Hosting accounts and domains provisioned instantly.
                                </p>
                            </div>

                            <div className="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-200 border border-gray-100 dark:border-gray-700">
                                <div className="w-12 h-12 bg-gradient-to-r from-blue-400 to-cyan-500 rounded-xl flex items-center justify-center mb-6">
                                    <span className="text-2xl">📊</span>
                                </div>
                                <h3 className="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Business Analytics</h3>
                                <p className="text-gray-600 dark:text-gray-300 leading-relaxed">
                                    Comprehensive reporting on revenue, customer growth, service utilization, and business performance.
                                </p>
                            </div>

                            <div className="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-200 border border-gray-100 dark:border-gray-700">
                                <div className="w-12 h-12 bg-gradient-to-r from-red-400 to-pink-500 rounded-xl flex items-center justify-center mb-6">
                                    <span className="text-2xl">🔧</span>
                                </div>
                                <h3 className="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Service Management</h3>
                                <p className="text-gray-600 dark:text-gray-300 leading-relaxed">
                                    Track hosting accounts, domain registrations, SSL certificates, and other services from one dashboard.
                                </p>
                            </div>

                            <div className="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-200 border border-gray-100 dark:border-gray-700">
                                <div className="w-12 h-12 bg-gradient-to-r from-indigo-400 to-purple-500 rounded-xl flex items-center justify-center mb-6">
                                    <span className="text-2xl">🔄</span>
                                </div>
                                <h3 className="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Workflow Automation</h3>
                                <p className="text-gray-600 dark:text-gray-300 leading-relaxed">
                                    Automated workflows from order placement to service delivery, reducing manual work and errors.
                                </p>
                            </div>
                        </div>

                        {/* Workflow Section */}
                        <div className="py-16">
                            <div className="text-center mb-12">
                                <h2 className="text-3xl md:text-4xl font-bold mb-4 text-gray-900 dark:text-gray-100">
                                    How It Works
                                </h2>
                                <p className="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                                    Simplified workflow that handles everything from order to provisioning automatically
                                </p>
                            </div>

                            <div className="grid md:grid-cols-4 gap-8">
                                <div className="text-center">
                                    <div className="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <span className="text-2xl text-white">📋</span>
                                    </div>
                                    <h3 className="text-lg font-semibold mb-2 text-gray-900 dark:text-gray-100">Customer Orders</h3>
                                    <p className="text-gray-600 dark:text-gray-300 text-sm">
                                        Customer places order for hosting or domain services
                                    </p>
                                </div>

                                <div className="text-center">
                                    <div className="w-16 h-16 bg-gradient-to-r from-green-500 to-teal-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <span className="text-2xl text-white">💳</span>
                                    </div>
                                    <h3 className="text-lg font-semibold mb-2 text-gray-900 dark:text-gray-100">Invoice Generated</h3>
                                    <p className="text-gray-600 dark:text-gray-300 text-sm">
                                        System automatically creates and sends invoice
                                    </p>
                                </div>

                                <div className="text-center">
                                    <div className="w-16 h-16 bg-gradient-to-r from-yellow-500 to-orange-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <span className="text-2xl text-white">✅</span>
                                    </div>
                                    <h3 className="text-lg font-semibold mb-2 text-gray-900 dark:text-gray-100">Payment Processed</h3>
                                    <p className="text-gray-600 dark:text-gray-300 text-sm">
                                        Payment confirmation triggers automation
                                    </p>
                                </div>

                                <div className="text-center">
                                    <div className="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <span className="text-2xl text-white">🚀</span>
                                    </div>
                                    <h3 className="text-lg font-semibold mb-2 text-gray-900 dark:text-gray-100">Service Provisioned</h3>
                                    <p className="text-gray-600 dark:text-gray-300 text-sm">
                                        Hosting/domain automatically activated
                                    </p>
                                </div>
                            </div>
                        </div>

                        {/* CTA Section */}
                        {!auth.user && (
                            <div className="text-center py-16">
                                <div className="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-12 text-white">
                                    <h2 className="text-3xl md:text-4xl font-bold mb-4">
                                        Ready to Transform Your Hosting Business?
                                    </h2>
                                    <p className="text-xl mb-8 opacity-90 max-w-2xl mx-auto">
                                        Join hundreds of hosting providers who have automated their operations with HostingHub
                                    </p>
                                    <Link
                                        href={route('register')}
                                        className="inline-flex items-center justify-center px-8 py-4 bg-white text-blue-600 font-bold rounded-xl hover:bg-gray-100 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1"
                                    >
                                        Start Your Free Trial Today 🎉
                                    </Link>
                                </div>
                            </div>
                        )}
                    </div>
                </main>

                <footer className="py-8 px-6 border-t border-gray-200 dark:border-gray-700">
                    <div className="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between">
                        <div className="flex items-center space-x-2 mb-4 md:mb-0">
                            <div className="w-6 h-6 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <span className="text-white text-sm">🏢</span>
                            </div>
                            <span className="font-semibold text-gray-900 dark:text-gray-100">HostingHub</span>
                        </div>
                        <p className="text-sm text-gray-600 dark:text-gray-400">
                            Built with ❤️ by{" "}
                            <a 
                                href="https://app.build" 
                                target="_blank" 
                                className="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300"
                            >
                                app.build
                            </a>
                        </p>
                    </div>
                </footer>
            </div>
        </>
    );
}
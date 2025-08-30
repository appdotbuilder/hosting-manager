import { AppShell } from '@/components/app-shell';
import { type BreadcrumbItem } from '@/types';

interface AppLayoutProps {
    children: React.ReactNode;
    breadcrumbs?: BreadcrumbItem[];
}

export default function AppLayout({ children }: AppLayoutProps) {
    return (
        <AppShell variant="header">
            {children}
        </AppShell>
    );
}
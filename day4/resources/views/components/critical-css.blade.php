{{-- Critical CSS để tránh FOUC khi chuyển trang --}}
<style>
    body { margin: 0; padding: 0; font-family: system-ui, -apple-system, sans-serif; background: #f9fafb; color: #111827; }
    
    /* Gray - Backgrounds, text */
    .bg-gray-bg, body { background-color: #f9fafb !important; }
    .bg-white { background-color: #ffffff !important; }
    .bg-gray-light { background-color: #f3f4f6 !important; }
    .bg-gray { background-color: #e5e7eb !important; }
    .bg-gray-border { background-color: #d1d5db !important; }
    .text-gray-text { color: #4b5563 !important; }
    .text-gray-strong { color: #374151 !important; }
    .text-gray-dark { color: #111827 !important; }
    
    /* Blue - Buttons, links chính */
    .bg-blue { background-color: #3b82f6 !important; }
    .bg-blue-strong { background-color: #2563eb !important; }
    .bg-blue-hover { background-color: #1d4ed8 !important; }
    .text-blue-strong { color: #2563eb !important; }
    .text-blue-hover { color: #1d4ed8 !important; }
    
    /* Red - Xóa, lỗi */
    .bg-red-light { background-color: #fee2e2 !important; }
    .bg-red { background-color: #ef4444 !important; }
    .bg-red-strong { background-color: #dc2626 !important; }
    .bg-red-hover { background-color: #b91c1c !important; }
    .text-red { color: #ef4444 !important; }
    .text-red-strong { color: #dc2626 !important; }
    .text-red-dark { color: #991b1b !important; }
    
    /* Green - Thành công, hoàn thành */
    .bg-green-light { background-color: #dcfce7 !important; }
    .bg-green { background-color: #22c55e !important; }
    .bg-green-strong { background-color: #16a34a !important; }
    .bg-green-hover { background-color: #15803d !important; }
    .text-green-strong { color: #16a34a !important; }
    .text-green-dark { color: #166534 !important; }
    
    /* Yellow - Cảnh báo */
    .bg-yellow-light { background-color: #fef9c3 !important; }
    .bg-yellow-strong { background-color: #ca8a04 !important; }
    .bg-yellow-hover { background-color: #a16207 !important; }
    .text-yellow-dark { color: #854d0e !important; }
    
    /* Common */
    .text-white { color: #ffffff !important; }
    .font-bold { font-weight: 700 !important; }
    .font-medium { font-weight: 500 !important; }
    .font-semibold { font-weight: 600 !important; }
    .text-2xl { font-size: 1.5rem; line-height: 2rem !important; }
    .text-3xl { font-size: 1.875rem; line-height: 2.25rem !important; }
    .text-sm { font-size: 0.875rem; line-height: 1.25rem !important; }
    .px-3 { padding-left: 0.75rem; padding-right: 0.75rem !important; }
    .px-4 { padding-left: 1rem; padding-right: 1rem !important; }
    .px-5 { padding-left: 1.25rem; padding-right: 1.25rem !important; }
    .py-1 { padding-top: 0.25rem; padding-bottom: 0.25rem !important; }
    .py-1\.5 { padding-top: 0.375rem; padding-bottom: 0.375rem !important; }
    .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem !important; }
    .py-3 { padding-top: 0.75rem; padding-bottom: 0.75rem !important; }
    .py-4 { padding-top: 1rem; padding-bottom: 1rem !important; }
    .py-8 { padding-top: 2rem; padding-bottom: 2rem !important; }
    .p-8 { padding: 2rem !important; }
    .my-12 { margin-top: 3rem; margin-bottom: 3rem !important; }
    .mb-2 { margin-bottom: 0.5rem !important; }
    .mb-5 { margin-bottom: 1.25rem !important; }
    .mb-6 { margin-bottom: 1.5rem !important; }
    .mb-8 { margin-bottom: 2rem !important; }
    .mt-1 { margin-top: 0.25rem !important; }
    .mt-5 { margin-top: 1.25rem !important; }
    .mt-6 { margin-top: 1.5rem !important; }
    .rounded { border-radius: 0.25rem !important; }
    .rounded-md { border-radius: 0.375rem !important; }
    .rounded-lg { border-radius: 0.5rem !important; }
    .shadow-sm { box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05) !important; }
    .max-w-7xl { max-width: 80rem !important; }
    .max-w-md { max-width: 28rem !important; }
    .mx-auto { margin-left: auto; margin-right: auto !important; }
    .flex { display: flex !important; }
    .block { display: block !important; }
    .inline { display: inline !important; }
    .items-center { align-items: center !important; }
    .justify-between { justify-content: space-between !important; }
    .flex-wrap { flex-wrap: wrap !important; }
    .gap-2 { gap: 0.5rem !important; }
    .gap-3 { gap: 0.75rem !important; }
    .gap-5 { gap: 1.25rem !important; }
    .w-full { width: 100% !important; }
    .border { border-width: 1px !important; }
    .border-gray-border { border-color: #d1d5db !important; }
    .border-l-4 { border-left-width: 4px !important; }
    .border-green { border-color: #22c55e !important; }
    .border-red { border-color: #ef4444 !important; }
    .border-collapse { border-collapse: collapse !important; }
    .overflow-x-auto { overflow-x: auto !important; }
    .text-center { text-align: center !important; }
    .text-left { text-align: left !important; }
    .space-y-6 > * + * { margin-top: 1.5rem !important; }
    .list-disc { list-style-type: disc !important; }
    .ml-5 { margin-left: 1.25rem !important; }
    .resize-y { resize: vertical !important; }
    .min-h-\[100px\] { min-height: 100px !important; }
    .no-underline { text-decoration: none !important; }
    .hover\:underline:hover { text-decoration: underline !important; }
    .hover\:bg-blue-hover:hover { background-color: #1d4ed8 !important; }
    .hover\:bg-red-hover:hover { background-color: #b91c1c !important; }
    .hover\:bg-green-hover:hover { background-color: #15803d !important; }
    .hover\:bg-yellow-hover:hover { background-color: #a16207 !important; }
    .hover\:bg-gray-border:hover { background-color: #d1d5db !important; }
    .hover\:bg-gray-light:hover { background-color: #f3f4f6 !important; }
    .hover\:bg-gray-bg:hover { background-color: #f9fafb !important; }
    .hover\:text-blue-hover:hover { color: #1d4ed8 !important; }
    .transition { transition-property: color, background-color, border-color, text-decoration-color, fill, stroke; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); transition-duration: 150ms !important; }
    .font-sans { font-family: system-ui, -apple-system, sans-serif !important; }
    .antialiased { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale !important; }
    input:focus, textarea:focus, select:focus { outline: none !important; }
    .focus\:outline-none:focus { outline: none !important; }
    .focus\:ring-2:focus { box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5) !important; }
    .focus\:ring-blue:focus { --tw-ring-color: #3b82f6 !important; }
    .focus\:border-transparent:focus { border-color: transparent !important; }
</style>

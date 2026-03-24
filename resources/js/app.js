import './bootstrap';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';

window.flatpickr = flatpickr;

window.portalShell = () => ({
    sidebarOpen: false,
    toggleSidebar() {
        this.sidebarOpen = !this.sidebarOpen;
    },
    closeSidebar() {
        this.sidebarOpen = false;
    },
});

window.portalDocumentWorkspace = () => ({
    modal: '',
    viewerOpen: false,
    viewerUrl: '',
    viewerTitle: '',
    viewerKind: 'image',
    uploadPreviewUrl: '',
    uploadPreviewTitle: '',
    uploadPreviewKind: '',

    openModal(name) {
        this.modal = name;
    },
    closeModal() {
        this.modal = '';
    },
    openViewer(url, title, kind = 'image') {
        this.viewerUrl = url;
        this.viewerTitle = title;
        this.viewerKind = kind;
        this.viewerOpen = true;
    },
    closeViewer() {
        this.viewerOpen = false;
        this.viewerUrl = '';
        this.viewerTitle = '';
        this.viewerKind = 'image';
    },
    handleUploadSelection(event) {
        const file = event.target.files?.[0];

        this.clearUploadSelection();

        if (!file) {
            return;
        }

        const extension = (file.name.split('.').pop() || '').toLowerCase();
        const isPdf = file.type === 'application/pdf' || extension === 'pdf';
        const isImage = file.type.startsWith('image/');

        if (!isPdf && !isImage) {
            return;
        }

        this.uploadPreviewUrl = URL.createObjectURL(file);
        this.uploadPreviewTitle = file.name;
        this.uploadPreviewKind = isPdf ? 'pdf' : 'image';
    },
    openUploadPreview() {
        if (!this.uploadPreviewUrl) {
            return;
        }

        this.openViewer(this.uploadPreviewUrl, this.uploadPreviewTitle, this.uploadPreviewKind);
    },
    clearUploadSelection() {
        const previousUrl = this.uploadPreviewUrl;

        if (previousUrl) {
            URL.revokeObjectURL(previousUrl);
        }

        this.uploadPreviewUrl = '';
        this.uploadPreviewTitle = '';
        this.uploadPreviewKind = '';

        if (this.viewerOpen && this.viewerUrl === previousUrl) {
            this.closeViewer();
        }
    },
});

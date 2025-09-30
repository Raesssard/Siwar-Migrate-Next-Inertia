import React from "react"

export default function FilePreview({ filePath, judul, displayStyle }) {
    // Ambil ekstensi file
    const extension = filePath.split(".").pop().toLowerCase()

    const openDocumentModal = (path, isPdf) => {
        // bikin sesuai logika modalmu
        console.log("Open:", path, "isPdf:", isPdf)
    }

    if (["pdf"].includes(extension)) {
        return (
            <div
                className="pdf-thumbnail-container cursor-pointer"
                onClick={() => openDocumentModal(filePath, true)}
            >
                <i className="far fa-file-pdf pdf-icon text-red-600 text-3xl"></i>
                <p className="pdf-filename">Lihat PDF</p>
            </div>
        )
    }

    if (["jpg", "jpeg", "png", "gif", "webp"].includes(extension)) {
        return (
            <img
                src={filePath}
                alt={`File ${judul ?? ""}`}
                onClick={() => openDocumentModal(filePath, false)}
                style={displayStyle}
            />
        )
    }

    if (["mp4", "mov", "avi", "mkv", "webm"].includes(extension)) {
        return (
            <video
                autoPlay
                muted
                loop
                className="video-preview max-w-[300px] rounded"
                style={displayStyle}>
                <source src={filePath} type={`video/${extension}`} />
                Browser tidak mendukung video ini.
            </video>
        )
    }

    if (["doc", "docx"].includes(extension)) {
        return (
            <div
                className="doc-thumbnail-container cursor-pointer"
                onClick={() => window.open(filePath, "_blank")}
                style={displayStyle}
            >
                <i className="far fa-file-word text-primary fa-3x"></i>
                <p className="doc-filename">Lihat Dokumen Word</p>
            </div>
        )
    }

    return (
        <p>
            <i className="fas fa-file"></i> File tidak didukung
        </p>
    )
}

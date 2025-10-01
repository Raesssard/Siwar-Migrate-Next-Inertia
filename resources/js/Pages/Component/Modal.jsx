import React, { useEffect, useRef, useState } from "react"
import { Link, useForm, usePage } from "@inertiajs/react"
import logo from '../../../../public/img/logo.png'
import { Inertia } from "@inertiajs/inertia"
import axios from "axios"


export function ModalSidebar({ modalIsOpen, modalShow }) {
    const { url } = usePage()

    const isActive = (url, pattern, exact = false) => {
        if (exact) {
            return url === pattern
        }
        return url.startsWith(pattern)
    }
    return (
        <>
            {modalIsOpen && (
                <div
                    className="modal fade show d-block"
                    style={{ backgroundColor: "rgba(0,0,0,0.5)" }}
                    onClick={() => modalShow(false)}
                >
                    <div
                        className={`modal-dialog modal-dialog-slideout-left modal-sm animasi-modal ${modalIsOpen ? "show" : ""}`}
                        onClick={(e) => e.stopPropagation()}
                    >
                        <div className="modal-content bg-primary text-white">
                            <div className="modal-header border-0">
                                <img src={logo} alt="Logo" className="sidebar-brand-icon-logo" />
                                <button className="close text-white" onClick={() => modalShow(false)}>
                                    ×
                                </button>
                            </div>
                            <div className="modal-body p-0">
                                <ul className="navbar-nav sidebar sidebar-dark accordion">
                                    <hr className="sidebar-divider my-0" />
                                    <li className={`nav-item ${isActive(url, '/warga', true) ? 'active' : ''}`}>
                                        <Link className="nav-link" href="/warga">
                                            <i className="fas fa-fw fa-tachometer-alt mr-2"></i>
                                            <span>Dashboard</span>
                                        </Link>
                                    </li>


                                    <li className={`nav-item ${isActive(url, '/warga/pengumuman') ? 'active' : ''}`}>
                                        <Link className="nav-link" href="/warga/pengumuman">
                                            <i className="fas fa-bullhorn mr-2"></i>
                                            <span>Pengumuman</span>
                                        </Link>
                                    </li>

                                    <li className={`nav-item ${isActive(url, '/warga/pengaduan') ? 'active' : ''}`}>
                                        <Link className="nav-link" href="/warga/pengaduan">
                                            <i className="fas fa-paper-plane mr-2"></i>
                                            <span>Pengaduan</span>
                                        </Link>
                                    </li>

                                    <li className={`nav-item ${isActive(url, '/warga/kk') ? 'active' : ''}`}>
                                        <Link className="nav-link" href="/warga/kk">
                                            <i className="fas fa-id-card mr-2"></i>
                                            <span>Lihat KK</span>
                                        </Link>
                                    </li>
                                    <li className={`nav-item ${isActive(url, '/warga/tagihan') ? 'active' : ''}`}>
                                        <Link className="nav-link" href="/warga/tagihan">
                                            <i className="fas fa-hand-holding-usd mr-2"></i>
                                            <span>Lihat Tagihan</span>
                                        </Link>
                                    </li>
                                    <li className={`nav-item ${isActive(url, '/warga/transaksi') ? 'active' : ''}`}>
                                        <Link className="nav-link" href="/warga/transaksi">
                                            <i className="fas fa-money-bill-wave mr-2"></i>
                                            <span>Lihat Transaksi</span>
                                        </Link>
                                    </li>
                                    <hr className="sidebar-divider d-none d-md-block" />
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </>
    )
}

export function PasswordModal({ show }) {
    useEffect(() => {
        const handleEsc = (e) => {
            if (e.key === "Escape") show(false)
        }
        document.addEventListener("keydown", handleEsc)
        return () => document.removeEventListener("keydown", handleEsc)
    }, [show])
    return (
        <>
            <div
                className="modal fade show"
                tabIndex="-1"
                style={{
                    display: "block",
                    backgroundColor: "rgba(0,0,0,0.5)",
                }}
                onClick={() => show(false)}
            >
                <div
                    className="modal-dialog modal-dialog-centered"
                    onClick={(e) => e.stopPropagation()}
                >
                    <div className="modal-content">
                        <form onSubmit={() => show(false)}>
                            <div className="modal-header">
                                <h5 className="modal-title">
                                    <i className="fas fa-key text-primary me-1"></i>{" "}
                                    Ubah Password
                                </h5>
                                <button
                                    type="button"
                                    className="btn-close"
                                    onClick={() => show(false)}
                                />
                            </div>
                            <div className="modal-body">
                                <div className="form-floating mb-3 position-relative">
                                    <input
                                        type="password"
                                        name="current_password"
                                        className="form-control py-2 px-4"
                                        id="current_password"
                                        placeholder="Password Lama"
                                        required
                                    />
                                    <label htmlFor="current_password">
                                        <i className="fas fa-lock me-2"></i>
                                        Password Lama
                                    </label>
                                </div>
                                <div className="form-floating mb-3 position-relative">
                                    <input
                                        type="password"
                                        name="password"
                                        className="form-control py-2 px-4"
                                        id="password"
                                        placeholder="Password Baru"
                                        required
                                        minLength="6"
                                    />
                                    <label htmlFor="password">
                                        <i className="fas fa-lock me-2"></i>
                                        Password Baru
                                    </label>
                                </div>
                                <div className="form-floating mb-3 position-relative">
                                    <input
                                        type="password"
                                        name="password_confirmation"
                                        className="form-control py-2 px-4"
                                        id="password_confirmation"
                                        placeholder="Konfirmasi Password Baru"
                                        required
                                    />
                                    <label htmlFor="password_confirmation">
                                        <i className="fas fa-lock me-2"></i>
                                        Konfirmasi Password
                                    </label>
                                </div>
                            </div>
                            <div className="modal-footer">
                                <button
                                    type="button"
                                    className="btn btn-secondary"
                                    onClick={() => show(false)}
                                >
                                    Batal
                                </button>
                                <button type="submit" className="btn btn-primary">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </>
    )
}

export function DetailPengumuman({ selectedData, detailShow, onClose }) {
    if (!detailShow || !selectedData) return null
    useEffect(() => {
        const handleEsc = (e) => {
            if (e.key === "Escape") onClose()
        }
        document.addEventListener("keydown", handleEsc)
        return () => document.removeEventListener("keydown", handleEsc)
    }, [onClose])
    return (
        <>
            <div className="modal fade show" tabIndex="-1" style={{
                display: "block",
                backgroundColor: "rgba(0,0,0,0.5)"
            }} onClick={onClose}>
                <div
                    className="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered"
                    onClick={(e) => e.stopPropagation()}
                >
                    <div className="modal-content shadow-lg border-0">
                        <div className="modal-header bg-success text-white">
                            <h5 className="modal-title mb-0">Detail Pengumuman</h5>
                            <button
                                type="button"
                                className="btn-close btn-close-white"
                                onClick={onClose}
                            />
                        </div>
                        <div className="modal-body px-4 pt-4 pb-3">
                            <h4 className="fw-bold text-success mb-3">{selectedData.judul}</h4>
                            <div className="d-flex flex-column align-items-start mb-3">
                                <span className="text-muted mb-1">
                                    <i className="bi bi-calendar me-1"></i>
                                    {new Date(selectedData.tanggal).toLocaleDateString("id-ID", {
                                        weekday: "long",
                                        day: "numeric",
                                        month: "long",
                                        year: "numeric"
                                    })}
                                </span>

                                {selectedData.id_rt ? (
                                    <span className="text-dark fw-semibold">
                                        <i className="me-1"></i>
                                        {`Dari RT: ${selectedData.rukun_tetangga?.rt ?? '-'}`}
                                    </span>
                                ) : (
                                    <span className="text-dark fw-semibold">
                                        <i className="me-1"></i>
                                        {`Dari RW: ${selectedData.rw?.nomor_rw ?? '-'}`}
                                    </span>
                                )}
                            </div>

                            <ul className="list-unstyled mb-3 small text-left">
                                <li><strong>Kategori:</strong> <span className="ms-1">{selectedData.kategori ?? '-'}</span></li>
                            </ul>
                            <hr className="my-3" />
                            <div className="mb-4">
                                <h5 className="fw-bold text-success mb-2">Isi Pengumuman:</h5>
                                <div className="border rounded bg-light p-3 text-left" style={{ lineHeight: "1.6" }}>
                                    {selectedData.isi}
                                </div>
                            </div>
                            <div className="mb-3">
                                <h5 className="fw-bold text-success mb-2">Dokumen Terlampir:</h5>
                                {selectedData.dokumen_path ? (
                                    <div className="border rounded bg-light p-3 d-flex align-items-center justify-content-between">
                                        <div>
                                            <i className="bi bi-file-earmark-text me-2"></i>
                                            <span>{selectedData.dokumen_name ?? 'Dokumen Terlampir'}</span>
                                            <small className="text-muted d-block mt-1">Klik tombol di samping untuk melihat atau mengunduh.</small>
                                        </div>
                                        <a href={selectedData.dokumen_url} target="_blank" className="btn btn-primary btn-sm">
                                            <i className="bi bi-download"></i> Unduh
                                        </a>
                                    </div>
                                ) : (
                                    <div className="text-muted p-3 border rounded bg-light">
                                        Tidak ada dokumen yang terlampir.
                                    </div>
                                )}
                            </div>
                        </div>
                        <div className="modal-footer bg-light border-0 justify-content-end py-2">
                            <button
                                className="btn btn-outline-success"
                                onClick={onClose}
                            >
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </>
    )
}

export function DetailPengaduan({ selectedData, detailShow, onClose, onUpdated, onDeleted, userData }) {
    const [komentar, setKomentar] = useState([])
    const [newKomentar, setNewKomentar] = useState("")
    const [captionExpanded, setCaptionExpanded] = useState(false)
    const [commentExpanded, setCommentExpanded] = useState({})
    const [isOverflowing, setIsOverflowing] = useState(false)
    const [isEdit, setIsEdit] = useState(false)
    const textRef = useRef(null)
    const komenRef = useRef(null)

    const toggleEdit = () => {
        setIsEdit(!isEdit)
    }
    const toggleExpand = (id) => {
        setCommentExpanded((prev) => ({
            ...prev,
            [id]: !prev[id],
        }))
    }

    useEffect(() => {
        if (textRef.current) {
            const el = textRef.current
            setIsOverflowing(el.scrollHeight > el.clientHeight)
        }
    }, [selectedData])

    useEffect(() => {
        setKomentar(
            (selectedData?.komentar || []).sort(
                (a, b) => new Date(b.created_at) - new Date(a.created_at)
            )
        )
    }, [selectedData])

    useEffect(() => {
        if (isEdit) return

        const handleEsc = (e) => {
            if (e.key === "Escape") onClose()
        }

        document.addEventListener("keydown", handleEsc)
        return () => document.removeEventListener("keydown", handleEsc)
    }, [isEdit, onClose])

    const handleSubmit = () => {
        if (!newKomentar.trim()) return

        axios.post(`/warga/pengaduan/${selectedData.id}/komentar`, {
            isi_komentar: newKomentar
        })
            .then(res => {
                setKomentar(prev => [res.data, ...prev])
                setNewKomentar("")
                if (komenRef.current) {
                    komenRef.current.scrollTo({
                        top: 0,
                        behavior: "smooth"
                    })
                }
            })
            .catch(err => console.error(err))
    }

    useEffect(() => {
        const handleEnter = (e) => {
            if (e.key === "Enter") handleSubmit()
        }
        document.addEventListener("keydown", handleEnter)
        return () => document.removeEventListener("keydown", handleEnter)
    }, [newKomentar, selectedData])

    if (!detailShow || !selectedData) return null

    const fileName = selectedData.file_name?.toLowerCase() || ""

    return (
        <>
            <div
                className="modal fade show"
                tabIndex="-1"
                style={{
                    display: "block",
                    backgroundColor: "rgba(0,0,0,0.5)"
                }}
                onClick={() => {
                    onClose()
                    setIsEdit(false)
                }}
            >
                <div
                    className="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered"
                    onClick={(e) => e.stopPropagation()}
                >
                    <div className="modal-content modal-komen shadow-lg border-0">
                        <div className="modal-body p-0">
                            {isEdit ? (
                                <EditPengaduan
                                    toggle={toggleEdit}
                                    pengaduan={selectedData}
                                    onUpdated={(updatedPengaduan) => {
                                        onUpdated(updatedPengaduan)
                                        setIsEdit(false)
                                    }}
                                    onDeleted={(id) => {
                                        if (onDeleted) onDeleted(id)
                                    }}
                                />
                            ) : (
                                <div className="d-flex flex-row modal-komen">
                                    <div className="flex-fill border-end bg-black d-flex align-items-center justify-content-center" style={{ maxWidth: "50%" }}>
                                        {selectedData.file_path ? (
                                            <>
                                                {fileName.endsWith(".jpg") || fileName.endsWith(".jpeg") || fileName.endsWith(".png") || fileName.endsWith(".gif") ? (
                                                    <img
                                                        src={`/storage/${selectedData.file_path}`}
                                                        alt={selectedData.file_name}
                                                        className="img-fluid"
                                                        style={{ maxHeight: "80vh", objectFit: "contain" }}
                                                    />
                                                ) : fileName.endsWith(".mp4") || fileName.endsWith(".webm") || fileName.endsWith(".avi") ? (
                                                    <video
                                                        src={`/storage/${selectedData.file_path}`}
                                                        controls
                                                        autoPlay
                                                        loop
                                                        className="w-100"
                                                        style={{ maxHeight: "80vh", objectFit: "contain" }}
                                                    />
                                                ) : fileName.endsWith(".pdf") ? (
                                                    <embed
                                                        src={`/storage/${selectedData.file_path}`}
                                                        type="application/pdf"
                                                        className="pdf-preview"
                                                    />
                                                ) : (
                                                    <div className="p-3 text-center text-white">
                                                        <i className="bi bi-file-earmark-text fs-1"></i>
                                                        <p className="mb-1">Dokumen Terlampir: {selectedData.file_name}</p>
                                                        <a href={`/storage/${selectedData.file_path}`} target="_blank" className="btn btn-primary btn-sm">
                                                            <i className="bi bi-download"></i> Unduh
                                                        </a>
                                                    </div>
                                                )}
                                            </>
                                        ) : (
                                            <div className="text-muted">Tidak ada media</div>
                                        )}
                                    </div>
                                    <div className="flex-fill d-flex flex-column" style={{ maxWidth: "50%" }}>
                                        <div className="p-3 border-bottom caption-section">
                                            {userData.nik === selectedData.nik_warga ? (
                                                <div className="d-flex justify-between">
                                                    <h5 className="fw-bold mb-1 mt-2">{selectedData.judul}</h5>
                                                    <button onClick={toggleEdit} title="Edit Pengaduan">
                                                        <i className="far fa-edit"></i>
                                                    </button>
                                                </div>
                                            ) : (
                                                <h5 className="fw-bold mb-1 mt-2">{selectedData.judul}</h5>
                                            )}
                                            <small className="text-muted">
                                                {selectedData.warga?.nama} • RT {selectedData.warga?.kartu_keluarga?.rukun_tetangga?.rt}/RW{" "}
                                                {selectedData.warga?.kartu_keluarga?.rw?.nomor_rw}
                                            </small>
                                            <p
                                                ref={textRef}
                                                className={`mt-2 isi-pengaduan ${captionExpanded ? "expanded" : "clamped"}`}
                                            >
                                                {selectedData.isi}
                                            </p>
                                            {isOverflowing && (
                                                <button
                                                    className="btn btn-link p-0 mt-1 text-decoration-none"
                                                    onClick={() => setCaptionExpanded(!captionExpanded)}
                                                >
                                                    {captionExpanded ? "lebih sedikit" : "selengkapnya"}
                                                </button>
                                            )}
                                        </div>
                                        <div className="flex-grow-1 overflow-auto p-3 komen-section" ref={komenRef}>
                                            {komentar.length > 0 ? (
                                                komentar.map((komen, i) => (
                                                    <div key={i} className="mb-3">
                                                        <small className="fw-bold"><strong>{komen.user?.nama}</strong></small>{" "}
                                                        <small className="text-muted">
                                                            {new Date(komen.created_at).toLocaleString("id-ID", {
                                                                day: "2-digit",
                                                                month: "short",
                                                                year: "numeric",
                                                                hour: "2-digit",
                                                                minute: "2-digit",
                                                            })}
                                                        </small>

                                                        <p
                                                            className={`mb-2 komen ${commentExpanded[komen.id]
                                                                ? "line-clamp-none"
                                                                : "line-clamp-3"
                                                                }`}
                                                        >
                                                            {komen.isi_komentar}
                                                        </p>

                                                        {komen.isi_komentar.length > 100 && (
                                                            <button
                                                                className="btn-expand btn btn-link p-0 text-decoration-none mt-0"
                                                                onClick={() => toggleExpand(komen.id)}
                                                            >
                                                                {commentExpanded[komen.id]
                                                                    ? "lebih sedikit"
                                                                    : "selengkapnya"}
                                                            </button>
                                                        )}
                                                        <hr className="my-0" />
                                                    </div>
                                                ))
                                            ) : (
                                                <p className="text-muted">Belum ada komentar</p>
                                            )}
                                        </div>
                                        <div className="komen p-3 border-top">
                                            <div className="input-group">
                                                <input
                                                    type="text"
                                                    className="form-control komen"
                                                    placeholder="Tambah komentar..."
                                                    value={newKomentar}
                                                    onChange={(e) => setNewKomentar(e.target.value)}
                                                />
                                                <button className="btn btn-primary my-0" type="button" onClick={handleSubmit}>
                                                    <i className="far fa-paper-plane"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </>
    )
}

export function EditPengaduan({ toggle, onUpdated, onDeleted, pengaduan }) {
    const { data, setData, put, processing, errors } = useForm({
        judul: pengaduan.judul || "",
        isi: pengaduan.isi || "",
        level: pengaduan.level || "",
        file: null,
    }, { forceFormData: true })

    const [previewUrl, setPreviewUrl] = useState(null)
    const [showAlert, setShowAlert] = useState(false)

    const deletePengaduan = () => {
        setShowAlert(true)
    }

    const confirmDelete = (e) => {
        e.preventDefault()
        setShowAlert(false)

        axios.delete(`/warga/pengaduan/${pengaduan.id}`)
            .then(res => {
                console.log(res.data.message)
                if (onDeleted) {
                    onDeleted(pengaduan.id)
                }
            })
            .catch(err => {
                console.error("Gagal hapus:", err.response?.data || err)
            })
    }

    const cancelDelete = (e) => {
        e.preventDefault()
        setShowAlert(false)
    }

    useEffect(() => {
        if (!pengaduan?.file_path) return

        const fileName = pengaduan.file_path.toLowerCase()

        if (fileName.endsWith(".jpg") || fileName.endsWith(".jpeg") || fileName.endsWith(".png") || fileName.endsWith(".gif")) {
            setPreviewUrl({ type: "image", src: pengaduan.file_path })
        } else if (fileName.endsWith(".mp4") || fileName.endsWith(".webm") || fileName.endsWith(".avi")) {
            setPreviewUrl({ type: "video", src: pengaduan.file_path })
        } else if (fileName.endsWith(".pdf")) {
            setPreviewUrl({ type: "pdf", src: pengaduan.file_path })
        } else {
            setPreviewUrl({ type: "other", name: pengaduan.file_name })
        }
    }, [pengaduan])

    useEffect(() => {
        const handleEsc = (e) => {
            if (e.key === "Escape") toggle()
        }
        document.addEventListener("keydown", handleEsc)
        return () => document.removeEventListener("keydown", handleEsc)
    }, [toggle])

    const handleFileChange = (e) => {
        const selectedFile = e.target.files[0]

        if (!selectedFile) {
            setPreviewUrl(null)
            setData("file", null)
            return
        }

        setData("file", selectedFile || null)

        const fileName = selectedFile.name.toLowerCase()

        if (fileName.endsWith(".jpg") || fileName.endsWith(".jpeg") || fileName.endsWith(".png") || fileName.endsWith(".gif")) {
            const reader = new FileReader()
            reader.onload = (ev) => setPreviewUrl({ type: "image", src: ev.target.result })
            reader.readAsDataURL(selectedFile)
        } else if (fileName.endsWith(".mp4") || fileName.endsWith(".webm") || fileName.endsWith(".avi")) {
            setPreviewUrl({ type: "video", src: URL.createObjectURL(selectedFile) })
        } else if (fileName.endsWith(".pdf")) {
            setPreviewUrl({ type: "pdf", src: URL.createObjectURL(selectedFile) })
        } else {
            setPreviewUrl({ type: "other", name: selectedFile.name })
        }
    }

    const handleSubmit = (e) => {
        e.preventDefault()
        const formData = new FormData()
        formData.append('judul', data.judul)
        formData.append('isi', data.isi)
        formData.append('level', data.level)
        if (data.file) formData.append('file', data.file)
        formData.append('_method', 'PUT')

        axios.post(`/warga/pengaduan/${pengaduan.id}`, formData)
            .then(res => {
                if (onUpdated) {
                    onUpdated({
                        ...pengaduan,
                        ...res.data
                    })
                }
            })
            .catch(err => {
                console.error('Error:', err.response?.data || err)
            })
    }

    const getFileUrl = (src) => {
        if (!src) return ""
        if (src.startsWith("data:")) return src
        if (src.startsWith("blob:")) return src
        return `/storage/${src}`
    }

    return (
        <div className="d-flex flex-row modal-komen">
            {/* Preview */}
            <div className="flex-fill border-end bg-black d-flex align-items-center justify-content-center" style={{ maxWidth: "50%" }}>
                <div id="preview">
                    {previewUrl && previewUrl.type === "image" && (
                        <img
                            src={getFileUrl(previewUrl.src)}
                            alt="Preview"
                            style={{ maxHeight: "80vh", objectFit: "contain" }}
                        />
                    )}
                    {previewUrl && previewUrl.type === "video" && (
                        <video
                            src={getFileUrl(previewUrl.src)}
                            controls
                            autoPlay
                            loop
                            style={{ maxHeight: "80vh", objectFit: "contain" }}
                        />
                    )}
                    {previewUrl && previewUrl.type === "pdf" && (
                        <embed
                            src={getFileUrl(previewUrl.src)}
                            type="application/pdf"
                            className="pdf-preview"
                        />
                    )}
                    {previewUrl && previewUrl.type === "other" && <p>File dipilih: {previewUrl.name}</p>}
                </div>
            </div>

            <div className="flex-fill d-flex flex-column" style={{ maxWidth: "50%" }}>
                <div className="p-3" style={{ height: "100%" }}>
                    <div className="d-flex justify-content-end w-100 mb-2">
                        <button
                            type="button"
                            onClick={() => toggle()}
                            title="Kembali"
                        >
                            <i className="fas fa-arrow-left"></i>
                        </button>
                    </div>

                    <form onSubmit={handleSubmit}>
                        <div className="mb-3">
                            <label className="form-label">Judul</label>
                            <input
                                name="judul"
                                type="text"
                                className="edit-judul form-control"
                                value={data.judul}
                                onChange={(e) => setData("judul", e.target.value)}
                                required
                            />
                        </div>

                        <div className="mb-3">
                            <label className="form-label">Isi</label>
                            <textarea
                                name="isi"
                                className="edit-isi form-control"
                                rows="4"
                                value={data.isi}
                                onChange={(e) => setData("isi", e.target.value)}
                                required
                            ></textarea>
                        </div>

                        <div className="mb-3">
                            <label className="form-label">Tujuan Pengaduan: </label>
                            <select
                                name="level"
                                className="edit-tujuan form-select"
                                value={data.level}
                                onChange={(e) => setData("level", e.target.value)}
                            >
                                <option value="rt">RT</option>
                                <option value="rw">RW</option>
                            </select>
                        </div>

                        <div className="mb-3">
                            <input
                                type="file"
                                id="fileInput"
                                name="file"
                                className="d-none"
                                accept="image/*,video/*,.pdf,.doc,.docx"
                                onChange={handleFileChange}
                            />
                            <button
                                type="button"
                                className="edit-file btn btn-outline-primary m-0"
                                title="Upload File"
                                onClick={() => document.getElementById('fileInput').click()}
                            >
                                <i className="fas fa-upload mr-2"></i>
                                <small>
                                    Upload File
                                </small>
                            </button>
                            {pengaduan?.file_name && !data.file && (
                                <small className="text-muted d-block mt-2">
                                    File lama: {pengaduan.file_name}
                                </small>
                            )}
                            {data.file && (
                                <small className="text-success d-block mt-2">
                                    File dipilih: {data.file.name}
                                </small>
                            )}
                        </div>

                        <div className="d-flex justify-content-between" style={{ marginTop: "auto" }}>
                            <button
                                type="button"
                                onClick={deletePengaduan}
                                className="btn btn-danger"
                            >
                                <i className="fas fa-trash mr-2"></i>
                                Hapus
                            </button>
                            <button type="submit" className="btn btn-primary">
                                <i className="fas fa-save mr-2"></i>
                                Simpan
                            </button>
                        </div>
                        {showAlert && (
                            <div className="alert-popup border rounded p-3 mt-3 bg-light shadow">
                                <p className="mb-2">Yakin mau hapus pengaduan ini?</p>
                                <div className="d-flex gap-2">
                                    <button type="button" onClick={confirmDelete} className="btn btn-danger">Ya, hapus</button>
                                    <button type="button" onClick={cancelDelete} className="btn btn-secondary">Batal</button>
                                </div>
                            </div>
                        )}
                    </form>
                </div>
            </div>
        </div>
    )
}

export function TambahPengaduan({ tambahShow, onClose, onAdded }) {
    const { data, setData, put, processing, errors } = useForm({
        judul: "",
        isi: "",
        level: "",
        file: null,
    }, { forceFormData: true })

    const [previewUrl, setPreviewUrl] = useState(null)

    useEffect(() => {
        const handleEsc = (e) => {
            if (e.key === "Escape") onClose()
        }

        document.addEventListener("keydown", handleEsc)
        return () => document.removeEventListener("keydown", handleEsc)
    }, [onClose])

    const handleFileChange = (e) => {
        const selectedFile = e.target.files[0]

        if (!selectedFile) {
            setPreviewUrl(null)
            setData("file", null)
            return
        }

        setData("file", selectedFile || null)

        const fileName = selectedFile.name.toLowerCase()

        if (fileName.endsWith(".jpg") || fileName.endsWith(".jpeg") || fileName.endsWith(".png") || fileName.endsWith(".gif")) {
            const reader = new FileReader()
            reader.onload = (ev) => setPreviewUrl({ type: "image", src: ev.target.result })
            reader.readAsDataURL(selectedFile)
        } else if (fileName.endsWith(".mp4") || fileName.endsWith(".webm") || fileName.endsWith(".avi")) {
            setPreviewUrl({ type: "video", src: URL.createObjectURL(selectedFile) })
        } else if (fileName.endsWith(".pdf")) {
            setPreviewUrl({ type: "pdf", src: URL.createObjectURL(selectedFile) })
        } else {
            setPreviewUrl({ type: "other", name: selectedFile.name })
        }
    }

    const handleSubmit = (e) => {
        e.preventDefault()
        const formData = new FormData()
        formData.append('judul', data.judul)
        formData.append('isi', data.isi)
        formData.append('level', data.level)
        if (data.file) formData.append('file', data.file)

        axios.post(`/warga/pengaduan`, formData)
            .then(res => {
                if (onAdded) {
                    onAdded(res.data)
                }
                onClose()
            })
            .catch(err => {
                console.error('Error:', err.response?.data || err)
            })

    }

    const getFileUrl = (src) => {
        if (!src) return ""
        if (src.startsWith("data:")) return src
        if (src.startsWith("blob:")) return src
        return `/storage/${src}`
    }

    if (!tambahShow) return null

    return (
        <>
            <div
                className="modal fade show"
                tabIndex="-1"
                style={{
                    display: "block",
                    backgroundColor: "rgba(0,0,0,0.5)"
                }}
                onClick={() => {
                    onClose()
                }}
            >
                <div
                    className="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered"
                    onClick={(e) => e.stopPropagation()}
                >
                    <div className="modal-content modal-komen shadow-lg border-0">
                        <div className="modal-body p-0">
                            <div className="d-flex flex-row modal-komen">
                                {previewUrl ? (
                                    <div className="flex-fill border-end bg-black d-flex align-items-center justify-content-center" style={{ maxWidth: "50%" }}>
                                        <div id="preview">
                                            {previewUrl && previewUrl.type === "image" && (
                                                <img
                                                    src={getFileUrl(previewUrl.src)}
                                                    alt="Preview"
                                                    style={{ maxHeight: "80vh", objectFit: "contain" }}
                                                />
                                            )}
                                            {previewUrl && previewUrl.type === "video" && (
                                                <video
                                                    src={getFileUrl(previewUrl.src)}
                                                    controls
                                                    autoPlay
                                                    loop
                                                    style={{ maxHeight: "80vh", objectFit: "contain" }}
                                                />
                                            )}
                                            {previewUrl && previewUrl.type === "pdf" && (
                                                <embed
                                                    src={getFileUrl(previewUrl.src)}
                                                    type="application/pdf"
                                                    className="pdf-preview"
                                                />
                                            )}
                                            {previewUrl && previewUrl.type === "other" && <p>File dipilih: {previewUrl.name}</p>}
                                        </div>
                                    </div>
                                ) : (
                                    ""
                                )}
                                <div className="flex-fill d-flex flex-column" style={previewUrl ? { maxWidth: "50%" } : { maxWidth: "100%" }}>
                                    <div className="p-3" style={{ height: "100%" }}>
                                        <form onSubmit={handleSubmit}>
                                            <div className="mb-3">
                                                <label className="form-label">Judul</label>
                                                <input
                                                    name="judul"
                                                    type="text"
                                                    className="edit-judul form-control"
                                                    value={data.judul}
                                                    onChange={(e) => setData("judul", e.target.value)}
                                                    required
                                                />
                                            </div>

                                            <div className="mb-3">
                                                <label className="form-label">Isi</label>
                                                <textarea
                                                    name="isi"
                                                    className="edit-isi form-control"
                                                    rows="4"
                                                    value={data.isi}
                                                    onChange={(e) => setData("isi", e.target.value)}
                                                    required
                                                ></textarea>
                                            </div>

                                            <div className="mb-3">
                                                <label className="form-label">Tujuan Pengaduan: </label>
                                                <select
                                                    name="level"
                                                    className="edit-tujuan form-select"
                                                    value={data.level}
                                                    onChange={(e) => setData("level", e.target.value)}
                                                >
                                                    <option value="">...</option>
                                                    <option value="rt">RT</option>
                                                    <option value="rw">RW</option>
                                                </select>
                                            </div>

                                            <div className="mb-3">
                                                <input
                                                    type="file"
                                                    id="fileInput"
                                                    name="file"
                                                    className="d-none"
                                                    accept="image/*,video/*,.pdf,.doc,.docx"
                                                    onChange={handleFileChange}
                                                />
                                                <button
                                                    type="button"
                                                    className="edit-file btn btn-outline-primary m-0"
                                                    title="Upload File"
                                                    onClick={() => document.getElementById('fileInput').click()}
                                                >
                                                    <i className="fas fa-upload mr-2"></i>
                                                    <small>
                                                        Upload File
                                                    </small>
                                                </button>
                                                {data.file && (
                                                    <small className="text-success d-block mt-2">
                                                        File dipilih: {data.file.name}
                                                    </small>
                                                )}
                                            </div>

                                            <div className="d-flex justify-content-end" style={{ marginTop: "auto" }}>
                                                <button type="submit" className="btn btn-primary">
                                                    <i className="fas fa-save mr-2"></i>
                                                    Simpan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </>
    )
}
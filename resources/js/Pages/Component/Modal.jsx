import React, { useEffect, useRef, useState } from "react"
import { Link, useForm, usePage } from "@inertiajs/react"
import logo from '../../../../public/img/logo.png'
import { Inertia } from "@inertiajs/inertia"
import axios from "axios"

// Modal sidebar buat mobile mah nanti aja
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

export function DetailPengaduan({ selectedData, detailShow, onClose }) {
    const [komentar, setKomentar] = useState([])
    const [newKomentar, setNewKomentar] = useState("")
    const [captionExpanded, setCaptionExpanded] = useState(false);
    const [commentExpanded, setCommentExpanded] = useState({});
    const [isOverflowing, setIsOverflowing] = useState(false);
    const textRef = useRef(null);

    const toggleExpand = (id) => {
        setCommentExpanded((prev) => ({
            ...prev,
            [id]: !prev[id],
        }))
    }

    useEffect(() => {
        if (textRef.current) {
            const el = textRef.current;
            setIsOverflowing(el.scrollHeight > el.clientHeight);
        }
    }, [selectedData]);

    useEffect(() => {
        setKomentar(selectedData?.komentar || [])
    }, [selectedData])

    useEffect(() => {
        console.log("Komentar terbaru:", komentar)
    }, [komentar])

    useEffect(() => {
        const handleEsc = (e) => {
            if (e.key === "Escape") onClose()
        }
        document.addEventListener("keydown", handleEsc)
        return () => document.removeEventListener("keydown", handleEsc)
    }, [onClose])

    const handleSubmit = () => {
        if (!newKomentar.trim()) return

        axios.post(`/warga/pengaduan/${selectedData.id}/komentar`, {
            isi_komentar: newKomentar
        })
            .then(res => {
                setKomentar(prev => [res.data, ...prev])
                setNewKomentar("")
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
                onClick={onClose}
            >
                <div
                    className="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered"
                    onClick={(e) => e.stopPropagation()}
                >
                    <div className="modal-content modal-komen shadow-lg border-0">
                        <div className="modal-body p-0">
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
                                            ) : (
                                                <div className="p-3 text-center text-white">
                                                    <i className="bi bi-file-earmark-text fs-1"></i>
                                                    <p className="mb-1">{selectedData.file_name}</p>
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
                                        <h5 className="fw-bold mb-1">{selectedData.judul}</h5>
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
                                    <div className="flex-grow-1 overflow-auto p-3 komen-section">
                                        {komentar.length > 0 ? (
                                            komentar.map((komen, i) => (
                                                <div key={i} className="mb-3">
                                                    <small className="fw-bold">{komen.user?.nama}</small>{" "}
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
                                                className="form-control"
                                                placeholder="Tambah komentar..."
                                                value={newKomentar}
                                                onChange={(e) => setNewKomentar(e.target.value)}
                                            />
                                            <button className="btn btn-primary my-0" type="button" onClick={handleSubmit}>
                                                <i className="fas fa-paper-plane"></i>
                                            </button>
                                        </div>
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

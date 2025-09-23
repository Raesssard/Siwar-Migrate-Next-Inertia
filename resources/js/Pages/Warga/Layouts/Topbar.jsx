import React from "react"
import { usePage, Link } from "@inertiajs/react"
import { useState } from "react"
import '../../../../css/warga/topbar.css'

export default function Topbar() {
    const { url, props } = usePage()
    const user = props.auth?.user
    const [showPasswordModal, setShowPasswordModal] = useState(false)

    // ambil segment
    const segments = url.split("/").filter(Boolean)
    const segment = segments[1] ?? segments[0] ?? ""

    let judulHalaman
    if (!segment && (url === "/" || url === "/dashboard-main")) {
        judulHalaman = "Dashboard"
    } else {
        switch (segment) {
            case "kk":
                judulHalaman = "Data Kartu Keluarga"
                break
            case "warga":
                judulHalaman = "Dashboard"
                break
            case "warga_pengumuman":
                judulHalaman = "Pengumuman"
                break
            case "tagihan":
                judulHalaman = "Tagihan Saya"
                break
            case "iuran":
                judulHalaman = "Iuran"
                break
            case "transaksi":
                judulHalaman = "Transaksi"
                break
            case "pengaduan":
                judulHalaman = "Pengaduan Saya"
                break
            default:
                judulHalaman =
                    segment.charAt(0).toUpperCase() +
                    segment.slice(1).replace(/-/g, " ")
        }
    }

    return (
        <nav className="navbar nav-top navbar-expand navbar-light bg-white topbar mb-4 sticky-top shadow">
            <button
                className="btn btn-link d-md-none rounded-circle mr-3"
                data-bs-toggle="modal"
                data-bs-target="#mobileSidebarModal"
            >
                <i className="fa fa-bars"></i>
            </button>

            <h1 className="h3 mb-0 text-gray-800 mx-2 text-truncate">
                {judulHalaman}
            </h1>

            <ul className="navbar-nav ml-auto">
                <li className="nav-item dropdown no-arrow position-relative">
                    <Link
                        className="nav-link dropdown-toggle"
                        href="#"
                        id="userDropdown"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <span className="mr-3 text-gray-600 small user-name-display">
                            {user?.nama}
                        </span>
                    </Link>
                    <div
                        className="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                        aria-labelledby="userDropdown"
                    >
                        <button
                            type="button"
                            className="btn btn-sm btn-warning dropdown-item"
                            onClick={() => setShowPasswordModal(true)}
                        >
                            <i className="fas fa-lock fa-sm fa-fw mr-2 text-gray-400"></i>{" "}
                            Ubah Password
                        </button>
                        <Link
                            href="/logout"
                            method="post"
                            as="button"
                            className="dropdown-item"
                        >
                            <i className="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>{" "}
                            Logout
                        </Link>
                    </div>
                </li>
            </ul>

            {/* modal ubah password */}
            {showPasswordModal && (
                <div
                    className="modal fade show"
                    style={{ display: "block" }}
                    tabIndex="-1"
                >
                    <div className="modal-dialog modal-dialog-centered">
                        <div className="modal-content">
                            <form action={route("update.password")} method="post">
                                <div className="modal-header">
                                    <h5 className="modal-title">
                                        <i className="fas fa-key text-primary me-1"></i>{" "}
                                        Ubah Password
                                    </h5>
                                    <button
                                        type="button"
                                        className="btn-close"
                                        onClick={() => setShowPasswordModal(false)}
                                    />
                                </div>
                                <div className="modal-body">
                                    <div className="form-floating mb-3 position-relative">
                                        <input
                                            type="password"
                                            name="current_password"
                                            className="form-control"
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
                                            className="form-control"
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
                                            className="form-control"
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
                                        onClick={() => setShowPasswordModal(false)}
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
            )}
        </nav>
    )
}

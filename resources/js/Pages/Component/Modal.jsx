import React, { useEffect, useState } from "react"
import { Link, usePage } from "@inertiajs/react"
import logo from '../../../../public/img/logo.png'

// Modal sidebar buat mobile mah nanti aja
export function ModalSidebar({ modalIsOpen, modalShow }) {
    const { url } = usePage()

    const isActive = (url, pattern) => {
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
                                    <li className={`nav-item ${isActive(url, '/warga') ? 'active' : ''}`}>
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
    return (
        <>
            <div
                className="modal fade show"
                style={{ display: "block", backgroundColor: "rgba(0,0,0,0.5)" }}
                tabIndex="-1"
            >
                <div className="modal-dialog modal-dialog-centered">
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
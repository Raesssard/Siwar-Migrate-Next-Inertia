import React, { useState } from "react"
import "../../../css/ModalSidebar.css"
// import "../../../css/layout.css"
import { Link } from "@inertiajs/react"

// Modal sidebar buat mobile mah nanti aja
export function ModalSidebar() {
    const [isOpen, setIsOpen] = useState(false)

    function isActive(path, currentUrl) {
        return currentUrl === path ? 'active' : ''
    }

    return (
        <>
            {/* Tombol buka sidebar hanya di mobile */}
            <button className="btn-open-sidebar d-md-none" onClick={() => setIsOpen(true)}>
                ☰
            </button>

            {/* Modal Sidebar */}
            {isOpen && (
                <div className="modal-backdrop" onClick={() => setIsOpen(false)}>
                    <div className="modal-sidebar" onClick={(e) => e.stopPropagation()}>
                        <div className="modal-header">
                            <img src={Logo} alt="Logo" className="sidebar-logo" />
                            <button className="btn-close" onClick={() => setIsOpen(false)}>
                                ×
                            </button>
                        </div>
                        <div className="modal-body">
                            <ul>
                                <li>
                                    <Link></Link>
                                </li>
                                <li>
                                    <Link></Link>
                                </li>
                                <li>
                                    <Link></Link>
                                </li>
                                <li>
                                    <Link></Link>
                                </li>
                                <li>
                                    <Link></Link>
                                </li>
                                <li>
                                    <Link></Link>
                                </li>
                                <li>
                                    <Link></Link>
                                </li>
                                <li>
                                    <Link></Link>
                                </li>
                                <li>
                                    <Link></Link>
                                </li>
                                <li>
                                    <Link></Link>
                                </li>
                                <li>
                                    <Link></Link>
                                </li>
                                <li>
                                    <Link></Link>
                                </li>
                                <li>
                                    <Link></Link>
                                </li>
                            </ul>
                            {/* <Sidebar /> */}
                        </div>
                    </div>
                </div>
            )}
        </>
    )
}

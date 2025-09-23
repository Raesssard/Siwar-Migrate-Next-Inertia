import '../../../../css/warga/sidebar.css'
import logo from '../../../../../public/img/logo.png'
import { library } from '@fortawesome/fontawesome-svg-core'
import { fas } from '@fortawesome/free-solid-svg-icons'
import { far } from '@fortawesome/free-regular-svg-icons'
import { fab } from '@fortawesome/free-brands-svg-icons'
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome"
import { Link, usePage } from "@inertiajs/react"
import React, { useState } from "react"

library.add(fas, far, fab)

export default function Sidebar({ toggleKeParent }) {
    const [toggle, setToggle] = useState("")
    const { auth } = usePage().props
    const { url } = usePage()
    const isActive = (url, pattern) => {
        return url.startsWith(pattern)
    }

    const toggleSidebar = (e) => {
        e.preventDefault()
        const tgl = !toggle ? "toggled" : ""

        setToggle(tgl)
        toggleKeParent(tgl)
    }

    const rotation = toggle ? 'right' : 'left'

    // jangan di hapus ntar ke pake klo mau dijadiin layout bersama
    // const role = () => {
    //     if (auth?.roles?.includes('admin')) return '/admin/dashboard'
    //     if (auth?.roles?.includes('rw')) return '/rw'
    //     if (auth?.roles?.includes('rt')) return '/rt'
    //     if (auth?.roles?.includes('warga')) return '/warga'
    //     return '/login'
    // }
    // const path = role()

    return (
        <>
            <ul className={`navbar-nav bg-gradient-primary sidebar sidebar-dark accordion d-none d-md-block ${toggle}`} id="accordionSidebar">

                <Link className="sidebar-brand" href="/warga">
                    <div className="sidebar-brand-icon">
                        <img src={logo} alt="SiWar Logo" className="sidebar-brand-icon-logo" />
                    </div>
                </Link>

                <hr className="sidebar-divider my-0" />

                <li className={`nav-item ${isActive(url, '/warga') ? 'active' : ''}`}>
                    <Link className="nav-link" href="/warga">
                        <FontAwesomeIcon icon={["fas", "tachometer-alt"]} className='mr-2' />
                        <span>Dashboard</span>
                    </Link>
                </li>


                <li className={`nav-item ${isActive(url, '/warga/pengumuman') ? 'active' : ''}`}>
                    <Link className="nav-link" href="/warga/pengumuman">
                        <FontAwesomeIcon icon={["fas", "bullhorn"]} className='mr-2' />
                        <span>Pengumuman</span>
                    </Link>
                </li>

                <li className={`nav-item ${isActive(url, '/warga/pengaduan') ? 'active' : ''}`}>
                    <Link className="nav-link" href="/warga/pengaduan">
                        <FontAwesomeIcon icon={["fas", "paper-plane"]} className='mr-2' />
                        <span>Pengaduan</span>
                    </Link>
                </li>

                <li className={`nav-item ${isActive(url, '/warga/kk') ? 'active' : ''}`}>
                    <Link className="nav-link" href="/warga/kk">
                        <FontAwesomeIcon icon={["fas", "id-card"]} className='mr-2' />
                        <span>Lihat KK</span>
                    </Link>
                </li>
                <li className={`nav-item ${isActive(url, '/warga/tagihan') ? 'active' : ''}`}>
                    <Link className="nav-link" href="/warga/tagihan">
                        <FontAwesomeIcon icon={["fas", "hand-holding-usd"]} className='mr-2' />
                        <span>Lihat Tagihan</span>
                    </Link>
                </li>
                <li className={`nav-item ${isActive(url, '/warga/transaksi') ? 'active' : ''}`}>
                    <Link className="nav-link" href="/warga/transaksi">
                        <FontAwesomeIcon icon={["fas", "money-bill-wave"]} className='mr-2' />
                        <span>Lihat Transaksi</span>
                    </Link>
                </li>

                <hr className="sidebar-divider d-none d-md-block" />

                <div className="text-center">
                    <button className="rounded-circle border-0" onClick={toggleSidebar} id="sidebarToggle">
                        <FontAwesomeIcon icon={["fas", `chevron-${rotation}`]} className='arrow-toggle' />
                    </button>
                </div>

            </ul>
        </>
    )
}
import React, { useState, useEffect, useRef } from "react"
import Layout from "../Layouts/Layout"
import { Head, Link, usePage, useForm } from "@inertiajs/react"
import { Inertia } from "@inertiajs/inertia"
import Masonry from "react-masonry-css"
import FileDisplay from "./Component/FileDisplay"
import '../../css/card.css'
import { DetailPengaduan } from "./Component/Modal"

export default function Pengaduan() {
    const { pengaduan,
        title,
        total_pengaduan } = usePage().props
    const { props } = usePage()
    const role = props.auth?.currentRole
    const user = props.auth?.user
    const [selected, setSelected] = useState(null)
    const [showModal, setShowModal] = useState(false)
    const cardBodyRef = useRef(null)
    const [showButton, setShowButton] = useState(false)
    const { get, data, setData } = useForm({
        komentar: '',
    })
    const modalDetail = (item) => {
        setSelected(item)
        setShowModal(true)
    }

    const scrollToTop = () => {
        if (cardBodyRef.current) {
            cardBodyRef.current.scrollTo({
                top: 0,
                behavior: "smooth"
            })
        }
    }

    useEffect(() => {
        const handleScroll = () => {
            if (cardBodyRef.current) {
                const scrollTop = cardBodyRef.current.scrollTop
                setShowButton(scrollTop > 50)
            }
        }

        const cardBody = cardBodyRef.current
        if (cardBody) {
            cardBody.addEventListener("scroll", handleScroll)
        }

        return () => {
            if (cardBody) {
                cardBody.removeEventListener("scroll", handleScroll)
            }
        }
    }, [])

    const breakpointColumnsObj = {
        default: 5,
        1100: 4,
        700: 3,
        500: 2
    }

    const imgStyle = {
        width: "100%",
        maxWidth: "350px",
        objectFit: "cover",
        marginBottom: "10px",
        borderRadius: "8px 8px 0 0",
        display: "block"
    }



    const statusLabel = (status) => {
        switch (status) {
            case "belum": return "Belum dibaca"
            case "sudah": return "Sudah dibaca"
            case "selesai": return "Selesai"
            default: return "Status tidak diketahui"
        }
    }

    const statusColor = (status) => {
        switch (status) {
            case "belum": return "yellow"
            case "sudah": return "blue"
            case "selesai": return "green"
            default: return "gray"
        }
    }

    const cardClick = (card) => {
        console.log(`Card ${card} ke klik`)
    }

    return (
        <Layout>
            <Head title={`${title} ${role.length <= 2
                ? role.toUpperCase()
                : role.charAt(0).toUpperCase() + role.slice(1)}`} />
            <form className="form-filter row g-2 px-3 pb-2 mb-2">
                <div className="col-md-5 col-12">
                    <div className="input-group input-group-sm">
                        <input
                            type="text"
                            name="search"
                            className="form-control"
                            placeholder="Cari Pengaduan..."
                        />
                        <button className="btn-filter btn btn-primary" type="submit">
                            <i className="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div className="col-md-7 col-12 d-flex flex-wrap gap-2">
                    <select
                        name="tahun"
                        className="form-select form-select-sm w-auto flex-fill my-2"
                    >
                        <option value="">Semua Tahun</option>
                    </select>
                    <select
                        name="bulan"
                        className="form-select form-select-sm w-auto flex-fill my-2"
                    >
                        <option value="">Semua Bulan</option>
                    </select>
                    <select
                        name="kategori"
                        className="form-select form-select-sm w-auto flex-fill my-2"
                    >
                        <option value="">Semua Pengaduan</option>
                        <option value="saya">Pengaduan Saya</option>
                        <option value="rt">Pengaduan RT</option>
                        <option value="rw">Pengaduan RW</option>
                    </select>
                    <button type="submit" className="btn-input btn btn-sm btn-primary flex-fill" title="Filter">
                        <i className="fas fa-filter"></i>
                    </button>
                    <Link href="/warga/pengaduan" className="btn-input btn btn-secondary btn-sm flex-fill my-auto" title="Reset">
                        <i className="fas fa-undo"></i>
                    </Link>
                    <button type="button" className="btn-input btn btn-sm btn-success flex-fill">
                        <i className="fas fa-plus mr-2"></i>
                        Buat Pengaduan
                    </button>
                </div>

            </form>
            <div className="d-flex justify-content-between align-items-center mb-3 mx-4 w-100">
                <div className="d-flex align-items-center gap-1">
                    <i className="fas fa-paper-plane me-2 text-primary"></i>
                    <span className="fw-semibold text-dark">
                        {total_pengaduan ?? 0} Pengaduan
                    </span>
                </div>

                <div className="text-muted">
                    Menampilkan {pengaduan.to} dari total {pengaduan.total} data
                </div>
            </div>
            <div className="col-12">
                <div ref={cardBodyRef} className="card-body pengaduan">
                    {pengaduan.length ? (
                        <>
                            <Masonry
                                breakpointCols={breakpointColumnsObj}
                                className="flex gap-4"
                                columnClassName="space-y-4"
                            >
                                {pengaduan.map((item, index) => (
                                    <div key={index} className="card-clickable" onClick={() => modalDetail(item)}>
                                        <FileDisplay
                                            filePath={`/storage/${item.file_path}`}
                                            judul={item.file_name}
                                            displayStyle={imgStyle} />
                                        <h2 className="font-semibold text-lg mb-2 text-left mx-3">{item.judul}</h2>
                                        <div className="text-sm text-gray-500 mb-2 mx-3 flex justify-between">
                                            <span><i className="fas fa-user"></i> {item.warga.nama}</span>
                                            <span><i className="fas fa-clock"></i> {new Date(item.created_at).toLocaleDateString("id-ID", {
                                                day: "2-digit",
                                                month: "short",
                                                year: "numeric",
                                            })}</span>
                                        </div>
                                        <p className="isi-pengaduan text-gray-700 text-sm mb-3 mx-3 line-clamp-3">
                                            {item.isi.length > 100 ? item.isi.slice(0, 100) + "..." : item.isi}
                                        </p>
                                        {item.warga?.nik === user.nik ?
                                            <span className={`px-2 py-1 rounded text-xs font-semibold bg-${statusColor(item.status)}-200 text-${statusColor(item.status)}-800`}>
                                                {statusLabel(item.status)}
                                            </span>
                                            :
                                            ""
                                        }
                                    </div>
                                ))}
                            </Masonry>
                            {showButton && (
                                <button
                                    onClick={scrollToTop}
                                    className={`btn btn-primary scroll-top-btn ${showButton ? "show" : ""}`}
                                >
                                    <i className="fas fa-arrow-up"></i>
                                </button>
                            )}
                        </>
                    ) : (
                        <span className="d-block w-100 text-muted text-center">Tidak ada pengaduan</span>
                    )}
                </div>
                <DetailPengaduan
                    selectedData={selected}
                    detailShow={showModal}
                    onClose={() => setShowModal(false)}
                />
            </div>
        </Layout>
    )
}
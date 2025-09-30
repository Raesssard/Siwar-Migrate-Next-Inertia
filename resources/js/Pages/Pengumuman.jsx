import React, { useState, useEffect } from "react"
import Layout from "../Layouts/Layout"
import { Head, Link, usePage, useForm } from "@inertiajs/react"
import { DetailPengumuman } from "./Component/Modal"

export default function Pengumuman() {
    const { title,
        pengumuman,
        list_bulan,
        daftar_tahun,
        daftar_kategori,
        total_pengumuman } = usePage().props
    const [selected, setSelected] = useState(null)
    const [showModal, setShowModal] = useState(false)
    const { props } = usePage()
    const { get, data, setData } = useForm({
        search: '',
        tahun: '',
        bulan: '',
        kategori: ''
    })

    const role = props.auth?.currentRole

    const modalDetail = (item) => {
        setSelected(item)
        setShowModal(true)
    }

    const filter = (e) => {
        e.preventDefault()
        get('/warga/pengumuman')
    }

    return (
        <Layout>
            <Head title={`${title} ${role.length <= 2
                ? role.toUpperCase()
                : role.charAt(0).toUpperCase() + role.slice(1)}`} />
            <form onSubmit={filter} className="form-filter row g-2 px-3 pb-2 mb-2">
                <div className="col-md-5 col-12">
                    <div className="input-group input-group-sm">
                        <input
                            type="text"
                            name="search"
                            value={data.search}
                            onChange={(e) => setData('search', e.target.value)}
                            className="form-control"
                            placeholder="Cari Judul/Isi/hari..."
                        />
                        <button className="btn-filter btn btn-primary" type="submit">
                            <i className="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <div className="col-md-7 col-12 d-flex flex-wrap gap-2">
                    <select
                        name="tahun"
                        value={data.tahun}
                        onChange={(e) => setData('tahun', e.target.value)}
                        className="form-select form-select-sm w-auto flex-fill my-2"
                    >
                        <option value="">Semua Tahun</option>
                        {daftar_tahun.map((th) => (
                            <option key={th} value={th}>
                                {th}
                            </option>
                        ))}
                    </select>

                    <select
                        name="bulan"
                        value={data.bulan}
                        onChange={(e) => setData('bulan', e.target.value)}
                        className="form-select form-select-sm w-auto flex-fill my-2"
                    >
                        <option value="">Semua Bulan</option>
                        {list_bulan.map((nama, index) => (
                            <option key={index + 1} value={index + 1}>
                                {nama.charAt(0).toUpperCase() + nama.slice(1)}
                            </option>
                        ))}
                    </select>

                    <select
                        name="kategori"
                        value={data.kategori}
                        onChange={(e) => setData('kategori', e.target.value)}
                        className="form-select form-select-sm w-auto flex-fill my-2"
                    >
                        <option value="">Semua Kategori</option>
                        {daftar_kategori.map((kt) => (
                            <option key={kt} value={kt}>
                                {kt}
                            </option>
                        ))}
                    </select>

                    <button type="submit" className="btn-input btn btn-sm btn-primary flex-fill">
                        Filter
                    </button>
                    <Link href="/warga/pengumuman" className="btn-input btn btn-secondary btn-sm flex-fill">
                        Reset
                    </Link>
                </div>
            </form>

            <div className="col-12">
                <div className="card shadow mb-4 py-0">
                    <div className="card-header py-auto d-flex flex-row align-items-center justify-content-between">
                        <h6 className="m-0 font-weight-bold text-primary">Daftar Pengumuman</h6>
                    </div>
                    <div className="card-body">
                        <div className="d-flex flex-wrap align-items-center justify-content-between mb-1">
                            <div className="d-flex align-items-center gap-1 mb-1 mb-sm-0">
                                <i className="fas fa-bullhorn me-2 text-primary"></i>
                                <span className="fw-semibold text-dark">{total_pengumuman} Pengumuman</span>
                            </div>
                        </div>
                        <div className="table-responsive table-container">
                            <table className="table table-sm table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Judul</th>
                                        <th>Kategori</th>
                                        <th>Ringkasan isi</th>
                                        <th>Tanggal</th>
                                        <th className="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {pengumuman.data.length ? (
                                        pengumuman.data.map((item, i) => (
                                            <tr key={item.id}>
                                                <td className="pt-3">{i + 1}</td>
                                                <td className="pt-3">{item.judul}</td>
                                                <td className="pt-3">{item.kategori}</td>
                                                {item.isi.length > 50 ? (
                                                    <td className="pt-3">{item.isi.slice(0, 50)}...</td>
                                                ) : (
                                                    <td className="pt-3">{item.isi.slice(0, item.isi.length)}</td>
                                                )}
                                                <td className="pt-3">{item.tanggal}</td>
                                                <td>
                                                    <button
                                                        type="button"
                                                        className="btn-aksi btn btn-success btn-sm my-2"
                                                        onClick={() => modalDetail(item)}
                                                    >
                                                        <i className="fas fa-book-open"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr>
                                            <td colSpan="6" className="text-center">
                                                Tidak ada pengumuman
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <DetailPengumuman
                        selectedData={selected}
                        detailShow={showModal}
                        onClose={() => setShowModal(false)}
                    />
                    <div className="d-flex flex-wrap justify-content-between align-items-center mb-3 px-4">
                        <div className="text-muted mb-2">
                            Menampilkan {pengumuman.from} - {pengumuman.to} dari total {pengumuman.total} data
                        </div>

                        <div className="d-flex flex-wrap gap-1">
                            {pengumuman.links.length > 3 &&
                                (pengumuman.links.map((link, index) => (
                                    link.url ? (
                                        <Link
                                            key={index}
                                            href={link.url}
                                            className={`px-3 py-1 border rounded ${link.active ? "bg-primary text-white" : "bg-light text-dark"
                                                }`}
                                            dangerouslySetInnerHTML={{ __html: link.label }}
                                            style={{ textDecoration: 'none' }}
                                        />
                                    ) : (
                                        <span
                                            key={index}
                                            className="px-3 py-1 border rounded text-muted"
                                            dangerouslySetInnerHTML={{ __html: link.label }}
                                            style={{ textDecoration: 'none', cursor: 'not-allowed' }}
                                        />
                                    )
                                )))
                            }
                        </div>
                    </div>
                </div>
            </div>
        </Layout>
    )
}
import React, { useState } from "react"
import Layout from "../Layouts/Layout"
import { Head, Link, usePage } from "@inertiajs/react"
import '../../css/card.css'
import { Inertia } from "@inertiajs/inertia"

export default function Pengumuman() {
    const { title,
        pengumuman,
        rukun_tetangga,
        daftar_tahun,
        daftar_bulan,
        daftar_kategori,
        total_pengumuman } = usePage().props
    const [search, setSearch] = useState("")
    const [tahun, setTahun] = useState("")
    const [bulan, setBulan] = useState("")
    const [kategori, setKategori] = useState("")
    const { props } = usePage()
    const role = props.auth?.currentRole

    const filter = (e) => {
        e.preventDefault()
        Inertia.get("/warga/pengumuman", { search, tahun, bulan, kategori }, {
            preserveState: true,
            replace: true,
        })
    }

    return (
        <Layout>
            <Head title={`${title} ${role.length <= 2
                ? role.toUpperCase()
                : role.charAt(0).toUpperCase() + role.slice(1)}`} />
            <form onSubmit={filter} className="row g-2 align-items-start px-3 pb-2">
                <div className="col-md-5 col-12 mb-2">
                    <div className="input-group input-group-sm">
                        <input
                            type="text"
                            name="search"
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                            className="form-control"
                            placeholder="Cari Judul/Isi/hari..."
                        />
                        <button className="btn btn-primary" type="submit">
                            <i className="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <div className="col-md-7 col-12 d-flex flex-wrap gap-2">
                    <select
                        name="tahun"
                        value={tahun}
                        onChange={(e) => setTahun(e.target.value)}
                        className="form-select form-select-sm w-auto flex-fill"
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
                        value={bulan}
                        onChange={(e) => setBulan(e.target.value)}
                        className="form-select form-select-sm w-auto flex-fill"
                    >
                        <option value="">Semua Bulan</option>
                        {daftar_bulan.map((bln) => (
                            <option key={bln} value={bln}>
                                {bln}
                            </option>
                        ))}
                    </select>

                    <select
                        name="kategori"
                        value={kategori}
                        onChange={(e) => setKategori(e.target.value)}
                        className="form-select form-select-sm w-auto flex-fill"
                    >
                        <option value="">Semua Kategori</option>
                        {daftar_kategori.map((kt) => (
                            <option key={kt} value={kt}>
                                {kt}
                            </option>
                        ))}
                    </select>

                    <button type="submit" className="btn btn-sm btn-primary flex-fill">
                        Filter
                    </button>
                    <Link href="/warga/pengumuman" className="btn btn-secondary btn-sm flex-fill">
                        Reset
                    </Link>
                </div>
            </form>

            <div className="col-12">
                <div className="card shadow mb-4">
                    <div className="card-header py-2 d-flex flex-row align-items-center justify-content-between">
                        <h6 className="m-0 font-weight-bold text-primary">Daftar Pengumuman</h6>
                    </div>
                    <div className="card-body">
                        <div class="d-flex flex-wrap align-items-center justify-content-between mb-1">
                            <div class="d-flex align-items-center gap-1 mb-1 mb-sm-0">
                                <i class="fas fa-bullhorn me-2 text-primary"></i>
                                <span class="fw-semibold text-dark">{total_pengumuman} Pengumuman</span>
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
                                                <td>{i + 1}</td>
                                                <td>{item.judul}</td>
                                                <td>{item.kategori}</td>
                                                <td>{item.isi.slice(0, 50)}...</td>
                                                <td>{item.tanggal}</td>
                                                <td className="text-center">
                                                    <button
                                                        type="button"
                                                        className="btn btn-success btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target={`#modalDetailPengumuman${item.id}`}
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

                    <div className="d-flex flex-wrap justify-content-between align-items-center mb-3 px-4">
                        <div className="text-muted mb-2">
                            Menampilkan {pengumuman.from} - {pengumuman.to} dari total {pengumuman.total} data
                        </div>

                        <div className="d-flex flex-wrap gap-1">
                            {pengumuman.links.map((link, index) => (
                                link.url ? (
                                    <Link
                                        key={index}
                                        href={link.url}
                                        className={`px-3 py-1 border rounded ${link.active ? "bg-primary text-white" : "bg-light text-dark"
                                            }`}
                                        dangerouslySetInnerHTML={{ __html: link.label }}
                                    />
                                ) : (
                                    <span
                                        key={index}
                                        className="px-3 py-1 border rounded text-muted"
                                        dangerouslySetInnerHTML={{ __html: link.label }}
                                    />
                                )
                            ))}
                        </div>
                    </div>

                </div>
            </div>
        </Layout>
    )
}
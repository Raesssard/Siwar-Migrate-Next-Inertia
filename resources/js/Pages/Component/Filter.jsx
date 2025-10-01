import React from "react"
import { Link } from "@inertiajs/react"

export function FilterPengaduan({ data, setData, list_tahun, list_bulan, list_level, filter, resetFilter, tambahShow }) {
    return (
        <form onSubmit={filter} className="form-filter row g-2 px-3 pb-2 mb-2">
            <div className="col-md-5 col-12">
                <div className="input-group input-group-sm">
                    <input
                        type="text"
                        name="search"
                        value={data.search}
                        onChange={(e) => setData('search', e.target.value)}
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
                    value={data.tahun}
                    onChange={(e) => setData('tahun', e.target.value)}
                    className="form-select form-select-sm w-auto flex-fill my-2"
                >
                    <option value="">Semua Tahun</option>
                    {list_tahun.map((th) => (
                        <option key={th} value={th}>{th}</option>
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
                    <option value="">Semua Pengaduan</option>
                    <option value="saya">Pengaduan Saya</option>
                    {list_level.map((level, index) => (
                        <option key={index} value={level}>Pengaduan {level.toUpperCase()}</option>
                    ))}
                </select>

                <button type="submit" className="btn-input btn btn-sm btn-primary flex-fill" title="Filter">
                    <i className="fas fa-filter"></i>
                </button>
                <Link
                    href="/warga/pengaduan"
                    preserveScroll
                    className="btn-input btn btn-secondary btn-sm flex-fill my-auto"
                    title="Reset"
                    onClick={resetFilter}
                >
                    <i className="fas fa-undo"></i>
                </Link>

                <button type="button" onClick={() => tambahShow()} className="btn-input btn btn-sm btn-success flex-fill">
                    <i className="fas fa-plus mr-2"></i>
                    Buat Pengaduan
                </button>
            </div>
        </form>
    )
}

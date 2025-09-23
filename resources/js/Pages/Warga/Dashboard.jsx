import React from "react";
import Layout from "./Layouts/Layout";
import { Head, Link, usePage } from "@inertiajs/react";
import '../../../css/warga/card.css'
import { library } from '@fortawesome/fontawesome-svg-core'
import { fas } from '@fortawesome/free-solid-svg-icons'
import { far } from '@fortawesome/free-regular-svg-icons'
import { fab } from '@fortawesome/free-brands-svg-icons'
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome"
import Topbar from "./Layouts/Topbar";

library.add(fas, far, fab)

export default function Dashboard(toggle) {
    const { jumlah_pengumuman,
        total_tagihan,
        total_transaksi,
        jumlah_tagihan,
        jumlah_transaksi,
        total_saldo_akhir,
        pengaduan } = usePage().props
    const formatRupiah = (angka) => {
        return "Rp. " + angka.toLocaleString("id-ID");
    };
    return (
        <Layout>
            <Head title="Dashboard" />
            <div id="content">
                <Topbar />
                <div className="container-fluid">
                    <div className="row">
                        <div className="col-xl-3 col-md-6 mb-4">
                            <div className="card border-left-warning shadow h-100 py-2 card-clickable">
                                <Link href="/warga/pengumuman" className="text-decoration-none">
                                    <div className="card-body">
                                        <div className="row no-gutters align-items-center">
                                            <div className="col mr-2">
                                                <div className="text-xs font-weight-bold text-warning text-uppercase mb-1 text-align-start">
                                                    Jumlah Pengumuman
                                                </div>
                                                <div className="h4 mb-0 font-weight-bolder text-gray-800">
                                                    {jumlah_pengumuman}
                                                </div>
                                            </div>
                                            <div className="col-auto">
                                                <i class="fas fa-comments text-gray-400" style={{ fontSize: '3rem' }}></i>
                                            </div>
                                        </div>
                                    </div>
                                </Link>
                            </div>
                        </div>
                        <div className="col-xl-3 col-md-6 mb-4">
                            <div className="card border-left-warning shadow h-100 py-2 card-clickable">
                                <Link href="/warga/pengaduan" className="text-decoration-none">
                                    <div className="card-body">
                                        <div className="row no-gutters align-items-center">
                                            <div className="col mr-2">
                                                <div className="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                    Pengaduan
                                                </div>
                                                <div className="h4 mb-0 font-weight-bolder text-gray-800">
                                                    {pengaduan}
                                                </div>
                                            </div>
                                            <div className="col-auto">
                                                <i class="fas fa-paper-plane text-gray-400" style={{ fontSize: '3rem' }}></i>
                                            </div>
                                        </div>
                                    </div>
                                </Link>
                            </div>
                        </div>
                        <div className="col-xl-3 col-md-6 mb-4">
                            <div className={`card border-left-${jumlah_tagihan < 1 ? 'success' : 'danger'} shadow h-100 py-2 card-clickable`}>
                                <Link href="/warga/tagihan" className="text-decoration-none">
                                    <div className="card-body">
                                        <div className="row no-gutters align-items-center">
                                            <div className="col mr-2">
                                                <div className={`text-xs font-weight-bold text-${jumlah_tagihan < 1 ? 'success' : 'danger'} text-uppercase mb-1`}>
                                                    Tagihan
                                                </div>
                                                <div className="h4 mb-0 font-weight-bolder text-gray-800">
                                                    {jumlah_tagihan}
                                                </div>
                                            </div>
                                            <div className="col-auto">
                                                <i class="fas fa-money-check-alt text-gray-400" style={{ fontSize: '3rem' }}></i>
                                            </div>
                                        </div>
                                    </div>
                                </Link>
                            </div>
                        </div>
                        <div className="col-xl-3 col-md-6 mb-4">
                            <div className={`card border-left-${total_tagihan < 1 ? 'success' : 'danger'} shadow h-100 py-2 card-clickable`}>
                                <Link href="/warga/tagihan" className="text-decoration-none">
                                    <div className="card-body">
                                        <div className="row no-gutters align-items-center">
                                            <div className="col mr-2">
                                                <div className={`text-xs font-weight-bold text-${total_tagihan < 1 ? 'success' : 'danger'} text-uppercase mb-1`}>
                                                    Total Tagihan
                                                </div>
                                                <div className="h4 mb-0 font-weight-bolder text-gray-800">
                                                    {formatRupiah(total_tagihan)}
                                                </div>
                                            </div>
                                            <div className="col-auto">
                                                <i class="fas fa-hand-holding-usd text-gray-400" style={{ fontSize: '3rem' }}></i>
                                            </div>
                                        </div>
                                    </div>
                                </Link>
                            </div>
                        </div>
                        <div className="col-xl-3 col-md-6 mb-4">
                            <div className="card border-left-primary shadow h-100 py-2 card-clickable">
                                <Link href="/warga/transaksi" className="text-decoration-none">
                                    <div className="card-body">
                                        <div className="row no-gutters align-items-center">
                                            <div className="col mr-2">
                                                <div className="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Transaksi
                                                </div>
                                                <div className="h4 mb-0 font-weight-bolder text-gray-800">
                                                    {jumlah_transaksi}
                                                </div>
                                            </div>
                                            <div className="col-auto">
                                                <i class="fas fa-money-bill-wave text-gray-400" style={{ fontSize: '3rem' }}></i>
                                            </div>
                                        </div>
                                    </div>
                                </Link>
                            </div>
                        </div>
                        <div className="col-xl-3 col-md-6 mb-4">
                            <div className="card border-left-primary shadow h-100 py-2 card-clickable">
                                <Link href="/warga/transaksi" className="text-decoration-none">
                                    <div className="card-body">
                                        <div className="row no-gutters align-items-center">
                                            <div className="col mr-2">
                                                <div className="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Total Transaksi
                                                </div>
                                                <div className="h4 mb-0 font-weight-bolder text-gray-800">
                                                    {formatRupiah(total_transaksi)}
                                                </div>
                                            </div>
                                            <div className="col-auto">
                                                <i class="fas fa-wallet text-gray-400" style={{ fontSize: '3rem' }}></i>
                                            </div>
                                        </div>
                                    </div>
                                </Link>
                            </div>
                        </div>
                        <div className="col-xl-3 col-md-6 mb-4">
                            <div className="card border-left-primary shadow h-100 py-2 card-clickable">
                                <Link href="/warga/transaksi" className="text-decoration-none">
                                    <div className="card-body">
                                        <div className="row no-gutters align-items-center">
                                            <div className="col mr-2">
                                                <div className="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Total Saldo
                                                </div>
                                                <div className="h4 mb-0 font-weight-bolder text-gray-800">
                                                    {formatRupiah(total_saldo_akhir)}
                                                </div>
                                            </div>
                                            <div className="col-auto">
                                                <i class="fas fa-wallet text-gray-400" style={{ fontSize: '3rem' }}></i>
                                            </div>
                                        </div>
                                    </div>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Layout>
    )
}
import React, { useState } from "react"
import "../../css/login.css"
import { Inertia } from "@inertiajs/inertia"
import { Head, usePage } from "@inertiajs/react"
import FloatingInput from './Component/FloatingInput'

export default function Login() {
    const { flash } = usePage().props;
    const [nik, setNik] = useState("")
    const [password, setPassword] = useState("")
    const [loading, setLoading] = useState(false)

    const handleSubmit = async (e) => {
        e.preventDefault()
        setLoading(true)

        Inertia.post(
            "/login",
            { nik, password },
            { onFinish: () => setLoading(false) }
        )
    }

    return (
        <>
            <Head title="Login" />
            <div className="container">
                <div className="card">
                    <div className="card-header">
                        <div className="logo">
                            <img src="/img/logo.png" alt="SiWar Logo" className="logo-img" />
                        </div>
                        <h3>Sistem Informasi Warga</h3>
                    </div>

                    <div className="card-body">
                        <h3 className="form-title">Log In</h3>

                        <form onSubmit={handleSubmit} className="needs-validation" noValidate>
                            <FloatingInput
                                label="NIK"
                                value={nik}
                                onChange={(e) => setNik(e.target.value)}
                                icon="bi-person-badge"
                            />

                            <FloatingInput
                                label="Kata Sandi"
                                type="password"
                                value={password}
                                onChange={(e) => setPassword(e.target.value)}
                                icon="bi-lock"
                                toggleable
                            />

                            {flash.error &&
                                <div className="alert mt-2">
                                    NIK atau kata sandi salah.
                                    <button type="button" className="custom-close" onClick={(e) => e.target.parentElement.remove()}>
                                        ×
                                    </button>
                                </div>
                            }


                            <button type="submit" className="btn btn-primary">
                                <i className="bi bi-box-arrow-in-right me-2"></i> {loading ? "Proses..." : "Masuk"}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </>
    )
}

Login.layout = null

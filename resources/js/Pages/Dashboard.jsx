import React from "react"
import Layout from "../Layouts/Layout"
import { Head, Link, usePage } from "@inertiajs/react"
import '../../css/card.css'
import Can from "./Component/Can"
import Role from "./Component/Role"
import Card from "./Component/Card"

export default function Dashboard() {
    const { props } = usePage()
    const { title } = usePage().props
    const role = props.auth?.currentRole
    return (
        <Layout>
            <Head title={`${title} ${role.length <= 2
                ? role.toUpperCase()
                : role.charAt(0).toUpperCase() + role.slice(1)}`}
            />
            <Card />
        </Layout>
    )
}
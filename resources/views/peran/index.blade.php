@extends('layouts.app')

@section('title', 'Role | HumasIn')

@section('page-title', 'Role')

@section('page-description', 'Daftar role yang tersedia dalam sistem')

@section('content')

<div style="max-width:1100px;">

    <div style="margin-bottom:20px;">
        <h3 style="font-size:16px;">
            
        </h3>

        <p style="
            font-size:12px;
            color:#9298a1;
            margin-top:5px;
        ">
          
        </p>
    </div>

    <div class="card">

        <div style="
            padding:20px;
            border-bottom:1px solid #edf0f3;
        ">
            <strong style="font-size:13px;">
                Daftar Role Sistem
            </strong>
        </div>

        <div style="overflow-x:auto;">

            <table style="
                width:100%;
                border-collapse:collapse;
                font-size:12px;
            ">

                <thead>
                    <tr style="
                        background:#fafbfc;
                        text-align:left;
                    ">
                        <th style="padding:14px 20px;">
                            No.
                        </th>

                        <th style="padding:14px 20px;">
                            Kode Role
                        </th>

                        <th style="padding:14px 20px;">
                            Nama Role
                        </th>

                        <th style="padding:14px 20px;">
                            Deskripsi
                        </th>

                        <th style="padding:14px 20px;">
                            Status
                        </th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($peran as $index => $item)

                        <tr style="
                            border-top:1px solid #edf0f3;
                        ">

                            <td style="padding:14px 20px;">
                                {{ $index + 1 }}
                            </td>

                            <td style="padding:14px 20px;">
                                {{ $item->kode_peran }}
                            </td>

                            <td style="padding:14px 20px;">
                                {{ $item->nama_peran }}
                            </td>

                            <td style="padding:14px 20px;">
                                {{ $item->deskripsi ?: '-' }}
                            </td>

                            <td style="padding:14px 20px;">

                                @if($item->aktif)
                                    <span style="
                                        padding:5px 10px;
                                        border-radius:20px;
                                        background:#edf7ef;
                                        color:#5c9b68;
                                    ">
                                        Aktif
                                    </span>
                                @else
                                    <span style="
                                        padding:5px 10px;
                                        border-radius:20px;
                                        background:#fceeee;
                                        color:#c75b5b;
                                    ">
                                        Tidak Aktif
                                    </span>
                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" style="
                                padding:40px;
                                text-align:center;
                                color:#9298a1;
                            ">
                                Belum ada data role.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
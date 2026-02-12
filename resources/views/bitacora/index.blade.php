@extends('layouts.app')

@section('title', 'Bitácora del Sistema')

@section('content')
    <div style="margin-bottom: 40px; animation: fadeInDown 0.8s ease-out;">
        <div>
            <span
                style="font-size: 10px; text-transform: uppercase; color: var(--proa-accent); font-weight: 900; letter-spacing: 2px; display: block; margin-bottom: 5px;">
                Auditoría y Seguridad
            </span>
            <h1 style="font-size: 32px; color: var(--proa-primary); font-weight: 900; margin: 0; letter-spacing: -1px;">
                Bitácora de <span style="color: var(--proa-primary-dark)">Operaciones</span>
            </h1>
        </div>
    </div>

    <div class="proa-card" style="animation: fadeInUp 0.8s ease-out; padding: 0;">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr
                        style="background: rgba(99, 17, 51, 0.03); color: var(--proa-primary); font-size: 11px; text-transform: uppercase; font-weight: 900; letter-spacing: 1px;">
                        <th style="padding: 20px; text-align: left;">Usuario</th>
                        <th style="padding: 20px; text-align: left;">Acción</th>
                        <th style="padding: 20px; text-align: left;">Descripción</th>
                        <th style="padding: 20px; text-align: center;">Dirección IP</th>
                        <th style="padding: 20px; text-align: right;">Fecha y Hora</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registros as $log)
                        <tr style="border-bottom: 1px solid rgba(99, 17, 51, 0.05);">
                            <td style="padding: 15px 20px;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div
                                        style="width: 32px; height: 32px; background: var(--proa-primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 900;">
                                        {{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}
                                    </div>
                                    <span
                                        style="font-weight: 700; color: var(--proa-primary-dark);">{{ $log->user->name ?? 'Sistema' }}</span>
                                </div>
                            </td>
                            <td style="padding: 15px 20px;">
                                <span class="proa-badge proa-badge-info" style="font-size: 10px;">
                                    {{ $log->accion }}
                                </span>
                            </td>
                            <td style="padding: 15px 20px; font-size: 13px; color: #666; max-width: 300px;">
                                {{ $log->descripcion }}
                            </td>
                            <td
                                style="padding: 15px 20px; text-align: center; font-family: monospace; color: var(--proa-accent);">
                                {{ $log->ip_address }}
                            </td>
                            <td style="padding: 15px 20px; text-align: right;">
                                <div style="font-weight: 700; color: var(--proa-primary);">
                                    {{ \Carbon\Carbon::parse($log->fecha)->format('d/m/Y') }}</div>
                                <div style="font-size: 11px; color: #999;">{{ $log->hora }}</div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($registros->hasPages())
            <div style="padding: 20px; border-top: 1px solid rgba(0,0,0,0.05);">
                {{ $registros->links() }}
            </div>
        @endif
    </div>
@endsection
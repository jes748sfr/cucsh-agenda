<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas CTA - {{ $evento->nombre }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #202945;
            color: #ffffff;
            padding: 24px 32px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
        }
        .header .badge {
            display: inline-block;
            margin-top: 8px;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .badge-nuevo,
        .badge-actualizado {
            background-color: #B12028;
            color: #ffffff;
        }
        .body {
            padding: 32px;
        }
        .section {
            margin-bottom: 24px;
        }
        .section-title {
            font-size: 14px;
            font-weight: 600;
            color: #202945;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #202945;
        }
        .detail-row {
            display: flex;
            padding: 6px 0;
        }
        .detail-label {
            font-weight: 600;
            color: #555555;
            min-width: 140px;
        }
        .detail-value {
            color: #333333;
        }
        table.fechas {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        table.fechas th {
            background-color: #202945;
            color: #ffffff;
            padding: 8px 12px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
        }
        table.fechas td {
            padding: 8px 12px;
            border-bottom: 1px solid #eeeeee;
            font-size: 14px;
        }
        table.fechas tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .notas-cta {
            background-color: #FFF8EC;
            border-left: 4px solid #FDCF85;
            padding: 16px 20px;
            border-radius: 0 4px 4px 0;
            margin-top: 8px;
        }
        .notas-cta p {
            margin: 0;
            white-space: pre-line;
        }
        .footer {
            background-color: #f5f5f5;
            padding: 16px 32px;
            text-align: center;
            font-size: 12px;
            color: #999999;
            border-top: 1px solid #eeeeee;
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Encabezado --}}
        <div class="header">
            <h1>{{ $evento->nombre }}</h1>
            <span class="badge {{ $esActualizacion ? 'badge-actualizado' : 'badge-nuevo' }}">
                {{ $esActualizacion ? 'Actualizacion de solicitud a CTA' : 'Solicitud de servicio a CTA' }}
            </span>
        </div>

        <div class="body">
            {{-- Notas CTA (seccion principal) --}}
            <div class="section">
                <div class="section-title">Notas para el CTA</div>
                <div class="notas-cta">
                    <p>{{ $evento->notas_cta }}</p>
                </div>
            </div>

            {{-- Informacion general --}}
            <div class="section">
                <div class="section-title">Informacion del evento</div>

                <div class="detail-row">
                    <span class="detail-label">Tipo:</span>
                    <span class="detail-value">{{ $evento->eventoTipo->nombre }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Institucion:</span>
                    <span class="detail-value">{{ $evento->institucion->nombre }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Organizador:</span>
                    <span class="detail-value">{{ $evento->organizador->nombre }} ({{ $evento->organizador->administracion->nombre }})</span>
                </div>

                @if ($evento->ubicacionRel)
                    <div class="detail-row">
                        <span class="detail-label">Ubicacion:</span>
                        <span class="detail-value">{{ $evento->ubicacionRel->nombre }}</span>
                    </div>
                @endif

                <div class="detail-row">
                    <span class="detail-label">Estado:</span>
                    <span class="detail-value">{{ $evento->activo ? 'Activo' : 'Inactivo' }}</span>
                </div>
            </div>

            {{-- Fechas --}}
            @if ($evento->fechas->isNotEmpty())
                <div class="section">
                    <div class="section-title">Fechas programadas</div>
                    <table class="fechas">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Hora inicio</th>
                                <th>Hora fin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($evento->fechas->sortBy('fecha') as $fecha)
                                <tr>
                                    <td>{{ $fecha->fecha->translatedFormat('d \d\e F \d\e Y') }}</td>
                                    <td>{{ $fecha->hora_inicio->format('H:i') }}</td>
                                    <td>{{ $fecha->hora_fin->format('H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Pie de pagina --}}
        <div class="footer">
            Este correo fue generado automaticamente por CUCSH Agenda.
            <br>Universidad de Guadalajara &mdash; Centro Universitario de Ciencias Sociales y Humanidades
        </div>
    </div>
</body>
</html>

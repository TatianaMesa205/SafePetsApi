@component('mail::message')

{{-- BLOQUE ESTILIZADO EN BEIGE --}}
<div style="background: #f7f1e8; padding: 25px; border-radius: 12px; border: 1px solid #e3d8c7;">

# 🐾 Cita cancelada

<br>

<p style="font-size: 15px; color: #5b4636;">
La cita ha sido cancelada correctamente. Aquí tienes la información detallada:
</p>

---

### 👤 Adoptante
**{{ $cita->adoptante->nombre_completo ?? 'Nombre no disponible' }}**

---

### 🐕 Mascota
**{{ $cita->mascota->nombre ?? 'N/A' }}**

---

@php
    $fecha = \Carbon\Carbon::parse($cita->fecha_cita)->format('Y-m-d');
    $hora = \Carbon\Carbon::parse($cita->fecha_cita)->format('H:i');
@endphp

### 📅 Fecha de la cita  
**{{ $fecha }}**

### ⏰ Hora de la cita (formato militar 24H)  
**{{ $hora }}**

---

### 📝 Motivo de la cita
<p style="background: #fff2e0; padding: 12px; border-radius: 8px; border: 1px solid #e8d9c9; color: #5b4636;">
    {{ $cita->motivo }}
</p>

---

### 📌 Estado
**Cancelada**


</div>
<br>
Gracias, Safe pets<br>


@endcomponent

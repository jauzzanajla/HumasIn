<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengingatKalender extends Model
{
    protected $table = 'pengingat_kalenders';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    /**
     * Bentuk data yang dipakai FullCalendar.
     */
    public function toCalendar(): array
    {
        $start = $this->tanggal->toDateString();

        if ($this->jam) {
            $start .= 'T' . substr((string) $this->jam, 0, 5);
        }

        return [
            'id'         => 'pengingat-' . $this->id,
            'title'      => $this->judul,
            'start'      => $start,
            'color'      => $this->warna,
            'classNames' => ['kal-pengingat'],
            'extendedProps' => [
                'type'       => 'pengingat',
                'rid'        => $this->id,
                'keterangan' => $this->keterangan,
            ],
        ];
    }
}
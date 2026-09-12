<?php

it('stores a valid tahun anggaran without fase_id', function () {
    $response = $this->post(route('tahun_anggarans.store'), [
        'tahun' => 2026,
        'nama_tahun_anggaran' => '2026',
        'status' => 'aktif',
    ]);

    $response->assertRedirect(route('tahun_anggarans.index'));
    $this->assertDatabaseHas('tahun_anggarans', [
        'tahun' => 2026,
        'nama_tahun_anggaran' => '2026',
        'status' => 'aktif',
    ]);
});

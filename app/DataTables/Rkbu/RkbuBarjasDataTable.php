<?php

namespace App\DataTables\Rkbu;

use App\Models\Rkbu\RkbuDetailBarjas;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RkbuBarjasDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('checkbox', function ($row) {
                return '
                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            value="'.$row->id.'">
                    </div>
                ';
            })
            ->addIndexColumn()

            ->filterColumn('nama_komponen', function ($query, $keyword) {

                $query->where(function ($q) use ($keyword) {

                    $q->where('nama_barang', 'like', "%{$keyword}%")
                        ->orWhere('spesifikasi', 'like', "%{$keyword}%");
                });
            })

            ->orderColumn('nama_komponen', function ($query, $order) {
                $query->orderBy('nama_barang', $order);
            })

            ->addColumn('nama_komponen', function ($row) {

                return '
                    <div class="d-flex flex-column">

                        <div class="d-flex flex-wrap gap-2 mb-3">

                            <span class="badge badge-light-primary">
                                Nomor RKBU :
                                '.e($row->rkbu?->nomor_rkbu).'
                            </span>

                            <span class="badge badge-light-success">
                                Sumber Dana :
                                '.e($row->rkbu?->sumberDana?->nama_sumber_dana ?? '-').'
                            </span>

                        </div>

                        <div class="d-flex align-items-start">

                            <div class="symbol symbol-50px me-4">
                                <div class="symbol-label bg-light-primary">
                                    <i class="ki-duotone ki-package fs-2 text-primary"></i>
                                </div>
                            </div>

                            <div class="flex-grow-1">

                                <div class="fw-bold fs-4 text-gray-900">
                                    '.e($row->nama_barang).'
                                </div>

                                <div class="text-muted fs-7 mb-2">
                                    ID : '.e($row->id).'
                                </div>

                                <div class="text-gray-700">
                                    <strong>Spesifikasi :</strong>
                                    '.e($row->spesifikasi).'
                                </div>

                            </div>

                        </div>

                    </div>
                    ';
            })
            ->addColumn('satuan', function ($row) {

                return '
                    <div class="text-end">
                            <span class="text-dark fs-6">
                                '.$row->volume_1.' '.$row->satuan_1.' x '.$row->volume_2.' '.$row->satuan_2.'
                            </span>
                    </div>
                ';
            })
            ->addColumn('harga_barang', function ($row) {

                return '
                    <div class="text-end">
                            <span class="text-dark fw-bold fs-6">
                                Rp.'.number_format(
                    $row->harga_satuan,
                    0,
                    ',',
                    '.'
                ).'
                            </span>
                    </div>
                ';
            })
            ->addColumn('total', function ($row) {

                return '
                    <div class="text-end">
                            <span class="text-dark fw-bold fs-6">
                                Rp.'.number_format(
                    $row->total_anggaran,
                    0,
                    ',',
                    '.'
                ).'
                            </span>
                    </div>
                ';
            })
            ->addColumn('action', function ($row) {

                $status = optional($row->rkbu)->status;

                $submitButton = '';
                $editButton = '';
                $deleteButton = '';

                // Submit / Resubmit
                if ($status == 'draft') {

                    $submitButton = '
        <button type="button"
            class="btn btn-icon btn-bg-light btn-active-color-success btn-sm me-1 btn-submit"
            data-id="'.$row->id.'"
            data-url="'.route('rkbu.barjas.submit', $row->id).'"
            title="Ajukan RKBU">

            <i class="fas fa-paper-plane"></i>

        </button>';
                } elseif ($status == 'revision') {

                    $submitButton = '
        <button type="button"
            class="btn btn-icon btn-bg-light btn-active-color-warning btn-sm me-1 btn-submit"
            data-id="'.$row->id.'"
            data-url="'.route('rkbu.barjas.submit', $row->id).'"
            title="Ajukan Ulang">

            <i class="fas fa-redo"></i>

        </button>';
                }

                // Edit & Delete hanya untuk Draft dan Revision
                if (in_array($status, ['draft', 'revision'])) {

                    $editButton = '
        <a href="'.route('rkbu.barjas.edit', $row->id).'"
            class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
            title="Edit">

            <span class="svg-icon svg-icon-3">
                <svg xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none">

                    <path opacity="0.3"
                        d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303Z"
                        fill="black"/>

                    <path
                        d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3Z"
                        fill="black"/>

                </svg>
            </span>

        </a>';

                    $deleteButton = '
        <button type="button"
            class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm btn-delete"
            data-id="'.$row->id.'"
            data-url="'.route('rkbu.barjas.destroy', $row->id).'"
            title="Hapus">

            <span class="svg-icon svg-icon-3">
                <svg xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none">

                    <path
                        d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z"
                        fill="black"/>

                </svg>
            </span>

        </button>';
                }

                return '
    <div class="d-flex justify-content-end flex-shrink-0">

        '.$submitButton.'

        <button type="button"
            class="btn btn-icon btn-bg-light btn-active-color-info btn-sm me-1 btn-show"
            data-id="'.$row->id.'"
            data-url="'.route('rkbu.barjas.show', $row->id).'"
            data-bs-toggle="modal"
            data-bs-target="#kt_modal_create_account"
            title="Detail">

            <span class="svg-icon svg-icon-3">
                <svg xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none">

                    <path
                        d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11Z"
                        fill="black"/>

                    <path opacity="0.3"
                        d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 22 17.5 22Z"
                        fill="black"/>

                </svg>
            </span>

        </button>

        '.$editButton.'

        '.$deleteButton.'

    </div>';
            })
            ->rawColumns([
                'checkbox',
                'nama_komponen',
                'satuan',
                'harga_barang',
                'total',
                'action',
            ]);
    }

    // public function query(RkbuDetailBarjas $model): QueryBuilder
    // {
    //     return $model->newQuery()

    //         ->with([
    //             'rkbu',
    //             'rkbu.sumberDana',
    //             'rkbu.subKategoriRkbu',
    //         ]);
    // }

    public function query(RkbuDetailBarjas $model): QueryBuilder
    {
        return $model->newQuery()
            ->with([
                'rkbu',
                'rkbu.sumberDana',
                'rkbu.subKategoriRkbu',
            ])
            ->whereHas('rkbu', function ($q) {
                $q->where('created_by', auth()->id()); // atau id_user
            });
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('rkbubarjas-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            // ->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload'),
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [

            Column::computed('checkbox')
                ->title('')
                ->width(30)
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->orderable(false),

            Column::computed('nama_komponen')
                ->title('Detail Komponen')
                ->searchable(true)
                ->orderable(true),

            Column::computed('satuan')
                ->title('Detail Volune'),

            Column::computed('harga_barang')
                ->title('Harga Barang'),

            Column::computed('total')
                ->title('Total Anggaran'),

            Column::computed('action')
                ->title('Action')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->orderable(false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'RkbuBarjas_'.date('YmdHis');
    }
}

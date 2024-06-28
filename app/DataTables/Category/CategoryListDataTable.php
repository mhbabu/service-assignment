<?php

namespace App\DataTables\Category;

use App\Models\Category;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Http\JsonResponse;

class CategoryListDataTable extends DataTable
{
    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function ajax(): JsonResponse
    {
        return datatables()
            ->eloquent($this->query())
            ->editColumn('status', function ($data) {
                return $data->status === 1 ? "<label class='badge bg-success'> Active </label>" : "<label class='badge bg-danger'> Inactive </label>";
            })
            ->addColumn('action', function ($data) {
                if(auth()->user()->is_admin){
                    $actionBtn = '<a href="' . route('categories.edit', $data->id) . '" class="btn btn-xs btn-primary btn-sm" title="Edit"> <i class="fa fa-edit"></i> Edit</a> ';
                    $actionBtn .= '<a href="' . route('categories.delete', $data->id) . '" class="btn btn-xs btn-danger btn-sm" title="Delete" onclick="return confirm(\'Are you sure you want to delete this item?\')"> <i class="fa fa-trash"></i> Delete</a>';
    
                    return $actionBtn;
                }
                
               return '-';

            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function getCategoryList()
    {
        return Category::latest();
    }

    /**s
     * Get query source of dataTable.
     * @return \Illuminate\Database\Eloquent\Builder
     * @internal param \App\Models\AgentBalanceTransactionHistory $model
     */
    public function query()
    {
        return $this->applyScopes($this->getCategoryList());
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->parameters([
                'dom' => 'Blfrtip',
                'responsive' => true,
                'autoWidth' => false,
                'paging' => true,
                "pagingType" => "full_numbers",
                'searching' => true,
                'info' => true,
                'searchDelay' => 350,
                "serverSide" => true,
                'order' => [[1, 'asc']],
                'buttons' => [],
                'pageLength' => 10,
                'lengthMenu' => [[10, 20, 25, 50, 100, 500, -1], [10, 20, 25, 50, 100, 500, 'All']],
                'language' => [
                    'lengthMenu' => '<span class="length-menu-text">Show</span> _MENU_ <span class="length-menu-text">entries</span>',
                    'paginate' => [
                    'first'    => '&laquo;',
                    'previous' => '&lsaquo;',
                    'next'     => '&rsaquo;',
                    'last'     => '&raquo;',
                ],
                ]
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'name'             => ['data' => 'name', 'name' => 'name', 'orderable' => true, 'searchable' => true],
            'status'           => ['data' => 'status', 'name' => 'status', 'orderable' => true, 'searchable' => false],
            'action'           => ['searchable' => false, 'orderable' => false]
        ];
    }
    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Category_List_' . date('Y_m_d_H_i_s') . '.json';
    }
}
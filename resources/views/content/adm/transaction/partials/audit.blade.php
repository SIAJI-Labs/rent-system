<div class="card mb-0 mt-2 collapsed-card">
    <div class="card-header">
        <h3 class="card-title">Audit</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
    <div class="card-body" style="display: none">
        <table class="table table-sm table-hover table-bordered table-striped">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Nilai Lama</th>
                    <th>Nilai Baru</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($audit as $item)
                    @foreach ($item->getModified() as $key => $dataLog)
                        @php
                            $oldValue = isset($dataLog['old']) ? $dataLog['old'] : null;
                            $newValue = isset($dataLog['new']) ? $dataLog['new'] : null;
                            $auditColumn = dynamicAuditColumn($item->auditable_type, $key, $oldValue, $newValue);
                        @endphp
                        <tr>
                            <td>
                                <span>{!! $auditColumn['column'] !!}</span>
                                @if (get_class(new \App\Models\TransactionItem) == $item->auditable_type)
                                    @php
                                        $transactionItemAudit = \App\Models\TransactionItem::find($item->auditable_id);
                                    @endphp
                                    <hr class="my-1"/>
                                    <small>(Item Transaksi{{ !empty($transactionItemAudit) ? ' / '.$transactionItemAudit->product->name.' - '.$transactionItemAudit->productDetail->serial_number : 'B' }})</small>
                                @endif
                            </td>
                            <td>{{ !empty($oldValue) ? $auditColumn['old'] : '-' }}</td>
                            <td>{{ !empty($newValue) ? $auditColumn['new'] : '-' }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>
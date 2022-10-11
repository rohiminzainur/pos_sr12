<div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="modal-detail">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Detail Purchase</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-detail">
                    <thead>
                        <th width="5%">No</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Subtotal</th>
                    </thead>
                    {{-- <tbody>
                        @foreach ($supplier as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->phone_number }}</td>
                                <td>{{ $item->address }}</td>
                                <td>
                                    <a href="{{ route('purchases.create', $item->id) }}"
                                        class="btn btn-primary btn-sm btn-flat"><i class="fa fa-check-circle"></i>
                                        Select</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody> --}}
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-product" tabindex="-1" role="dialog" aria-labelledby="modal-product">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Select Product</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-product">
                    <thead>
                        <th width="5%">No</th>
                        <th>code</th>
                        <th>Name</th>
                        <th>Purchase Price</th>
                        <th><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($product as $key => $item)
                            <tr>
                                <td width="5%">{{ $key + 1 }}</td>
                                <td>{{ $item->code_product }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->purchase_price }}</td>
                                <td>
                                    <a href="#"
                                        onclick="selectProduct('{{ $item->id }}', '{{ $item->code_product }}')"
                                        class="btn btn-primary btn-sm btn-flat"><i class="fa fa-check-circle"></i>
                                        Select</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

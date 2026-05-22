<table>
    <thead>
        <tr>
            <th>SKU</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price (₦)</th>
            <th>Sale Price (₦)</th>
            <th>Stock</th>
            <th>Unit</th>
            <th>Status</th>
            <th>Featured</th>
            <th>Tags</th>
            <th>Date Added</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr>
            <td>{{ $product->sku }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->category?->name }}</td>
            <td>{{ number_format($product->price, 2) }}</td>
            <td>{{ $product->sale_price ? number_format($product->sale_price, 2) : '' }}</td>
            <td>{{ $product->stock_quantity }}</td>
            <td>{{ $product->unit }}</td>
            <td>{{ $product->is_active ? 'Active' : 'Inactive' }}</td>
            <td>{{ $product->is_featured ? 'Yes' : 'No' }}</td>
            <td>{{ is_array($product->tags) ? implode(', ', $product->tags) : $product->tags }}</td>
            <td>{{ $product->created_at->format('d/m/Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

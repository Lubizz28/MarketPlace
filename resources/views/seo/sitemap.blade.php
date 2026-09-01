{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    {{-- 1. Static Core Landing Pages --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route('catalog') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ route('blog.index') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('register', ['type' => 'reseller']) }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>

    {{-- 2. Category Archive Pages --}}
    @foreach($categories as $category)
        <url>
            <loc>{{ route('category.show', $category->slug) }}</loc>
            <lastmod>{{ optional($category->updated_at)->toAtomString() ?? now()->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach

    {{-- 3. Product Detail Pages --}}
    @foreach($products as $product)
        <url>
            <loc>{{ route('product.show', $product->slug) }}</loc>
            <lastmod>{{ optional($product->updated_at)->toAtomString() ?? now()->toAtomString() }}</lastmod>
            <changefreq>daily</changefreq>
            <priority>0.9</priority>
            @if($product->primaryImage && $product->primaryImage->image_path)
                <image:image>
                    <image:loc>{{ asset($product->primaryImage->image_path) }}</image:loc>
                    <image:title>{{ $product->name }}</image:title>
                    <image:caption>{{ $product->name }} - Koleksi Busana Muslim MedinaStyle</image:caption>
                </image:image>
            @endif
        </url>
    @endforeach

    {{-- 4. Blog Posts --}}
    @foreach($posts as $post)
        <url>
            <loc>{{ route('blog.show', $post->slug) }}</loc>
            <lastmod>{{ optional($post->updated_at)->toAtomString() ?? now()->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.7</priority>
        </url>
    @endforeach

    {{-- 5. Static CMS Pages --}}
    @foreach($pages as $page)
        <url>
            <loc>{{ route('pages.show', $page->slug) }}</loc>
            <lastmod>{{ optional($page->updated_at)->toAtomString() ?? now()->toAtomString() }}</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.6</priority>
        </url>
    @endforeach
</urlset>

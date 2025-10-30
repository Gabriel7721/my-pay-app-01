//@ts-nocheck
import { Head, Link, router } from '@inertiajs/react';

export default function Products({ products }) {
    const add = (pid, qty = 1) =>
        router.post('/cart/add', { product_id: pid, qty });

    return (
        <div className="mx-auto max-w-5xl p-6">
            <Head title="Cà phê Việt – Sản phẩm" />
            <h1 className="mb-4 text-2xl font-semibold">Featured Products</h1>

            <div className="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                {products.map((p) => {
                    const stockRaw = p.stock ?? 0;
                    const inStock = Number(stockRaw) > 0;

                    return (
                        <div
                            key={p.id}
                            className={`rounded-xl border p-4 ${!inStock ? 'opacity-60' : ''}`}
                        >
                            {p.image_url && (
                                <img
                                    src={p.image_url}
                                    alt={p.name}
                                    className="mb-3 rounded"
                                />
                            )}
                            <h3 className="font-medium"></h3>
                            <p className="mb-2 text-sm text-gray-600"></p>

                            <div className="flex items-center justify-between">
                                <span className="font-semibold"></span>$
                                {Number(p.price).toLocaleString('en-US', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2,
                                })}
                                {inStock ? (
                                    <button
                                        onClick={() => add(p.id, 1)}
                                        className="rounded bg-black px-3 py-1 text-white hover:bg-gray-800"
                                    >
                                        + Add To Cart
                                    </button>
                                ) : (
                                    <span>OUT OF STOCK</span>
                                )}
                            </div>

                            <div className="mt-2 text-xs text-gray-500">
                                Stock: {Number(stockRaw)}
                            </div>
                        </div>
                    );
                })}
            </div>

            <div className="mt-6">
                <Link href={'/cart'}>Go to Cart</Link>
            </div>
        </div>
    );
}

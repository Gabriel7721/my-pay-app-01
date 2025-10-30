//@ts-nocheck
import { Head, Link, router, useForm, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';

export default function Cart({ items, total }) {
  const { props } = usePage(); const flash = props.flash ?? {};
  const [draftQty, setDraftQty] = useState({}); const [errItem, setErrItem] = useState({});
  useEffect(() => { const init = {}; items.forEach(it => (init[it.product.id] = it.qty)); setDraftQty(init); }, [items]);
  const clamp = (n) => Math.max(1, Number.isFinite(+n) ? Math.trunc(+n) : 1);
  const postQty = (pid, qty) => {
    const q = clamp(qty); setDraftQty(s => ({ ...s, [pid]: q })); setErrItem(s => ({ ...s, [pid]: '' }));
    router.post('/cart/update', { product_id: pid, qty: q }, {
      preserveScroll: true, preserveState: true, replace: true,
      onError: (errors) => setErrItem(s => ({ ...s, [pid]: errors?.qty || 'Failed to update quantity.' })),
      onSuccess: () => setErrItem(s => ({ ...s, [pid]: '' })),
    });
  };
  const remove = (pid) => router.post('/cart/remove', { product_id: pid }, { preserveScroll: true });
  const clear = () => router.post('/cart/clear', {}, { preserveScroll: true });

  return (
    <div className="mx-auto max-w-3xl p-6">
      <Head title="Cart" />
      <h1 className="mb-4 text-xl font-semibold">Cart</h1>

      {flash.error && <div className="mb-3 rounded border border-red-300 bg-red-50 px-3 py-2 text-sm text-red-700">{flash.error}</div>}
      {flash.success && <div className="mb-3 rounded border border-green-300 bg-green-50 px-3 py-2 text-sm text-green-700">{flash.success}</div>}

      {items.length === 0 ? (
        <p>Empty Carts. <Link href="/products" className="underline">Continue to shopping...</Link></p>
      ) : (
        <div>
          <ul className="space-y-3">
            {items.map((it, idx) => {
              const pid = it.product.id; const val = draftQty[pid] ?? it.qty;
              return (
                <li key={idx} className="flex items-center gap-3">
                  {it.product.image_url && <img src={it.product.image_url} className="h-16 w-16 rounded object-cover" />}
                  <div className="flex-1">
                    <div className="font-medium">{it.product.name}</div>
                    <div className="text-sm text-gray-600">$ {it.product.price.toLocaleString('en-US')} / per</div>

                    <div className="mt-2">
                      <div className="inline-flex items-center">
                        <button className="h-full rounded-l border px-3" onClick={() => postQty(pid, clamp(+val - 1))}>−</button>
                        <input
                          type="number" inputMode="numeric" pattern="[0-9]*"
                          className={`w-16 border-y px-2 py-1 text-center outline-none ${errItem[pid] ? 'border-red-400' : ''}`}
                          value={val} min={1}
                          onChange={(e) => {
                            const v = e.target.value;
                            if (v === '') { setDraftQty(s => ({ ...s, [pid]: '' })); setErrItem(s => ({ ...s, [pid]: 'Quantity must be higher than 0.' })); }
                            else if (!Number.isNaN(parseInt(v, 10))) {
                              const n = parseInt(v, 10);
                              setDraftQty(s => ({ ...s, [pid]: n }));
                              setErrItem(s => ({ ...s, [pid]: n < 1 ? 'Quantity must be higher than 0.' : '' }));
                            }
                          }}
                          onBlur={() => postQty(pid, clamp(val === '' ? 1 : +val))}
                          onKeyDown={(e) => { if (e.key === 'Enter') e.currentTarget.blur(); }}
                        />
                        <button className="h-full rounded-r border px-3" onClick={() => postQty(pid, clamp(+val + 1))}>＋</button>
                      </div>
                      {errItem[pid] && <div className="mt-1 text-xs text-red-600">{errItem[pid]}</div>}
                    </div>
                  </div>

                  <div className="font-semibold">$ {it.line.toLocaleString('en-US')}</div>
                  <button className="ml-3 text-red-600" onClick={() => remove(pid)}>X</button>
                </li>
              );
            })}
          </ul>

          <div className="mt-6 flex items-center justify-between">
            <button className="text-gray-600 underline" onClick={clear}>Remove Cart</button>
            <div className="text-right">
              <div className="text-sm text-gray-600">Total</div>
              <div className="text-2xl font-bold">$ {total.toLocaleString('en-US')}</div>
            </div>
          </div>

          <CheckoutForm />
        </div>
      )}
    </div>
  );
}

function CheckoutForm() {
  const { post, data, setData } = useForm({ provider: 'momo' });
  return (
    <form className="mt-6 space-y-4" onSubmit={(e) => { e.preventDefault(); post('/checkout/start'); }}>
      <h2 className="font-medium">Choose Payment Gateways</h2>
      <div className="grid grid-cols-2 gap-3">
        {['momo', 'vnpay', 'stripe', 'paypal'].map((p) => (
          <label key={p} className={`flex items-center gap-2 rounded border p-3 ${data.provider === p ? 'ring-2 ring-black' : ''}`}>
            <input type="radio" name="provider" value={p} checked={data.provider === p} onChange={() => setData('provider', p)} /> {p.toUpperCase()}
          </label>
        ))}
      </div>
      <button className="rounded bg-black px-4 py-2 text-white">Pay</button>
    </form>
  );
}

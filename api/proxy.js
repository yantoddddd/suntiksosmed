export default async function handler(req, res) {
    // Set CORS
    res.setHeader('Access-Control-Allow-Origin', '*');
    
    const { url, textOnly, compress } = req.query;
    
    if (!url) {
        return res.status(400).send('URL tidak ditemukan');
    }
    
    try {
        // Fetch website target
        const response = await fetch(url, {
            headers: {
                'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            }
        });
        
        let html = await response.text();
        
        // Mode text-only (buat hemat kuota)
        if (textOnly === 'true') {
            // Hapus script, style, gambar
            html = html.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '');
            html = html.replace(/<style\b[^<]*(?:(?!<\/style>)<[^<]*)*<\/style>/gi, '');
            html = html.replace(/<img\b[^>]*>/gi, '');
            html = html.replace(/<link\b[^>]*>/gi, '');
        }
        
        // Mode kompresi (hilangkan whitespace)
        if (compress === 'true') {
            html = html.replace(/\s+/g, ' ');
            html = html.replace(/>\s+</g, '><');
        }
        
        // Ganti semua link relatif jadi absolut
        const baseUrl = new URL(url);
        html = html.replace(/(href|src)=["'](?!https?:\/\/)([^"']+)["']/gi, (match, attr, path) => {
            const absolute = new URL(path, baseUrl).href;
            return `${attr}="${absolute}"`;
        });
        
        // Kirim response
        res.setHeader('Content-Type', 'text/html; charset=utf-8');
        res.status(200).send(html);
        
    } catch (error) {
        console.error('Proxy error:', error);
        res.status(500).send(`
            <!DOCTYPE html>
            <html>
            <head><title>Error</title></head>
            <body style="font-family:system-ui;padding:50px;text-align:center">
                <h1>⚠️ Gagal memuat website</h1>
                <p>${error.message}</p>
                <a href="/proxy.html">← Kembali</a>
            </body>
            </html>
        `);
    }
              }

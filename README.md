# TeleportRequest

Bu eklenti, oyuncular arasında ışınlanma isteği gönderip almayı sağlar.  
PocketMine-MP sunucuları için hazırlanmıştır.

---

## Komutlar

| Komut   | Açıklama                                      |
|---------|-----------------------------------------------|
| `/tpa <oyuncu>`  | Belirtilen oyuncuya ışınlanma isteği gönderir. İstek 10 saniye içinde kabul edilmezse zaman aşımına uğrar. |
| `/tpak`          | Gelen teleport ışınlanma kabul eder.                              |
| `/tpar`          | Gelen teleport ışınlanma reddeder.                               |

---

## Özellikler

- Bir oyuncu başka bir oyuncuya `/tpa` komutuyla teleport isteği gönderir.
- İstek gönderildikten sonra 10 saniyelik bir zaman aşımı başlar.
- İstek kabul edilirse (`/tpak`), gönderen oyuncu hedef oyuncunun yanına ışınlanır.
- Aynı anda yalnızca bir aktif ışınlanma isteği olabilir.

---

## Kurulum

1. Eklentiyi `plugins` klasörüne atın.
2. Sunucunuzu başlatın.
3. Komutları kullanmaya başlayın.

---

## Örnek Kullanım

/tpa <oyuncu> # Oyuncuya ışınlanma isteği gönderir.
/tpak # Gelen ışınlanma isteğini kabul eder.
/tpar # Gelen ışınlanma isteğini reddeder.
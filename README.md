
# APIK




## Installation



```bash
  >> Siapkan API ISB
  >> Clone the repo "git clone https://github.com/yogijowo/apik.git"
  >> Maukan API di `ApiDataModel.php` models/ApiDataModel
```
    
## Contoh

```javascript
public function getDataPenyedia() {
        // URL API Penyedia
        $api_penyedia_url = 'XXXX'; // GANTI XXXX DENGAN URL API Penyedia
        $data_penyedia = file_get_contents($api_penyedia_url);
        return json_decode($data_penyedia, true);
    }
```


## Teknologi

**Framework:** Codeigniter 3

**Client:** PHP, Bootstrap 5, Popper, DataTables, ApexCharts, jQuery

**Web Server:** Apache, sejenisnya


# KåKeramik - API
## API
### Utvecklare: Jeanette Krantz 

Detta projekt är del av projektuppgiften i kursen Fullstacksutveckling med ramverk (DT193G), på Mittuniversitetet i Sundsvall. REST-webbtjänsten är kopplad till en MySQL-databas med tabeller som lagrar företaget KåKeramiks produktlager. 

### Tabeller
Det finns tre olika tabeller i APIet, och alla tre har varsin Model och Controller:

- products, ProductModel, ProductController
- categories, CategoryModel, CategoryController
- documents, DocumentModel, DocumentController


### Routes
I alla controllers finns fullt stöd för CRUD. Utöver detta finns ett antal speciella anrop som kan göras till APIet. 

Route::get('/products/search/name/{name}', [ProductController::class, 'searchProduct'])->middleware('auth:sanctum');
> Söka efter produkter med produktnamn. Kräver autentisering. 

Route::post('/addcategory', [CategoryController::class, 'addCategory'])->middleware('auth:sanctum');
> Lägga till en kategori. Kräver autentisering.

Route::post('/addproduct/{id}', [CategoryController::class, 'addProduct'])->middleware('auth:sanctum');
> Lägga till en produkt som tillhör en specifik kategori. Kräver autentisering.

Route::post('/editproduct/{id}', [ProductController::class, 'editProduct'])->middleware('auth:sanctum');
> Ändra i en redan existerande produkt i en specifik kategori. Kräver autentisering.

Route::get('/getproducts/{id}', [CategoryController::class, 'getProductsByCategory'])->middleware('auth:sanctum');
> Hämta alla produkter som tillhör en specifik kategori. Kräver autentisering.

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
> Logga ut en användare. Kräver autentisering.

Route::post('/register', [AuthController::class, 'register']);
> Skapa en ny användare och returnera token

Route::post('/login', [AuthController::class, 'login']);
> Logga in en användare och returnera token

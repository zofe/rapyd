# Rapyd Admin: Simplifying Laravel Development

<a href="https://github.com/zofe/rapyd-admin/actions/workflows/run-tests.yml"><img src="https://github.com/zofe/rapyd-admin/actions/workflows/run-tests.yml/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/zofe/rapyd-admin"><img src="https://img.shields.io/packagist/dt/zofe/rapyd-admin" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/zofe/rapyd-admin"><img src="https://img.shields.io/packagist/v/zofe/rapyd-admin" alt="Latest Stable Version"></a>

[![rapyd.dev](screencast.gif)](https://rapyd.dev/demo)


## Installation

Create new laravel app, for example with laravel installer:

```bash
composer global require laravel/installer

laravel new myapp
```

You can install the package via composer:

```bash
cd myapp
composer require zofe/rapyd-admin
```

Then you can build home/login and permission preset with:

```bash
php artisan rpd:make:home
php artisan rpd:auth
```

Now you can login with 
```
admin@laravel
admin
```

---

## What is Rapyd Admin?

Rapyd Admin enhances a standard Laravel installation by providing essential features of an admin application, adopting a modular approach. Here's what it offers:

1. **Layout Module:**
    - Provides frontend/admin layout with a classic sidebar/navbar design.
    - Based on SBAdmin 3, now ported to Bootstrap 5.3 with customizable SCSS for colors and styles.

2. **Auth Module:**
    - Includes robust authentication features such as login/sign-in with socialite integration.
    - Supports Fortify and Two-Factor Authentication (2FA).
    - Manages roles, permissions, and user impersonation functionalities.

3. **Custom Modules (Your "X" Modules):**
    - Rapyd Admin introduces a structured way to handle components (using Livewire) and modules.
    - Generates components, modules, REST API endpoints, etc., providing a starting point for development.
    - Emphasizes a modular architecture, enabling the isolation of functionalities into separate micro-applications.

4. **Standard BALL Stack Environment:**
    - Bundles Bootstrap CSS, Alpine.js, Laravel, and Livewire.
    - Serves as a quick boilerplate for Laravel admin panels, ensuring rapid development.

## How Does Rapyd Admin Help?

Rapyd Admin is designed to streamline and accelerate the development of large-scale Laravel applications by offering:

- **Modular Approach:**
    - Developers can organize backends into reusable modules, encapsulating every aspect including components, views, tests, translations, migrations, and jobs.
    - Each module functions as an isolated Laravel application, promoting cleaner code organization.

- **Livewire Component-based Development:**
    - No need for controllers; each component is inherently reactive.
    - Simplifies development with fewer Livewire classes and Blade views, resulting in easily maintainable and testable code.

- **Blade Component-based Frontend:**
    - Provides a variety of anonymous components for frontend standardization.
    - Offers specialized tags based on Bootstrap, which can be extended as needed.

In essence, Rapyd Admin empowers developers to build Laravel applications more efficiently, leveraging a modular and component-based architecture for enhanced productivity and maintainability.

## Generators

rapyd has some commands to generate components, views, modules (bundled components & views isolated in a folder) via artisan command line:

```bash
php artisan rpd:make {ComponentName} {Model}
php artisan rpd:make UserTable User
```

will generate 

```
laravel/
├─ app/
│  ├─ Livewire/
│  │  ├─ UserTable.php
│  resources/
│  │  ├─ views/
│  │  │  ├─livewire/
│  │  │  │  ├─ user_table.php
```



## Modules

example of out of the box module structure you can use after installing rapyd-admin.

```bash
php artisan rpd:make {ComponentsName} {Model} --module={module}
php artisan rpd:make Articles Article --module=Blog
```
- Will create `Blog` folder in you app/Modules directory.
- Three livewire components in the `Livewire` subfolder (ArticlesEdit, ArticlesTable, ArticlesView)
- Three blade components in the `Views` subfolder (articles_edit, articles_table, articles_view)
- Inside your Module folder you can reply (if needed) the laravel application folder structure (controllers, migrations, jobs, etc..)


```
laravel/
├─ app/
│  ├─ Modules/
│  │  ├─ Blog/
│  │  │  ├─ Livewire/
│  │  │  │  ├─ ArticlesEdit.php
│  │  │  │  ├─ ArticlesTable.php
│  │  │  │  ├─ ArticlesView.php
│  │  │  ├─ Views/
│  │  │  │  ├─ articles_edit.blade.php
│  │  │  │  ├─ articles_table.blade.php
│  │  │  │  ├─ articles_view.blade.php
│  │  │  ├─ routes.php
```


## Crud Components

---
### Table
A Table is a "listing component" with these features:
- "input filters" to search in a custom data set 
- "buttons" (for example "add" record or "reset" filters)
- "pagination links"
- "sort links" 


```html
<x-rpd::table
    title="Article List"
    :items="$items"
>

    <x-slot name="filters">
      <x-rpd::input col="col-8" debounce="350" model="search"  placeholder="search..." />
      <x-rpd::select col="col-4" model="author_id" :options="$authors" placeholder="author..." addempty />
    </x-slot>

    <table class="table">
        <thead>
        <tr>
            <th>
                <x-rpd::sort model="id" label="id" />
            </th>
            <th>title</th>
            <th>author</th>
            <th>body</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($items as $article)
        <tr>
            <td>
                <a href="{{ route('articles.view',$article->id) }}">{{ $article->id }}</a>
            </td>
            <td>{{ $article->title }}</td>
            <td>{{ $article->author->firstname }}</td>
            <td>{{ Str::limit($article->body,50) }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>

</x-rpd::table>
```

props
- `title`: the heading title for this crud

content/slots
- should be a html table that loops model $items
- `buttons`: buttons panel

example: [rapyd.dev/demo/articles](https://rapyd.dev/demo/articles)


---
### View
a iew is a "detail page component" with :  

- "buttons" slot (for example back to "list" or "edit" current record)
- "actions" any link that trigger a server-side  

```html
    <x-rpd::view title="Article Detail">

        <x-slot name="buttons">
            <a href="{{ route('articles') }}" class="btn btn-outline-primary">list</a>
            <a href="{{ route('articles.edit',$model->getKey()) }}" class="btn btn-outline-primary">edit</a>
        </x-slot>

        <div>Title: {{ $article->title }}</div>
        <div>Author: {{ $article->author->firstname }} {{ $model->author->lastname }}</div>
        <div><a wire:click.prevent="someAction">Download TXT version</a></div>
          
    </x-rpd::view>
```

props
- `title`: the heading title for this crud

content/slots
- should be a detail of $model
- `buttons`: buttons panel
- `actions`: buttons panel

example: [rapyd.dev/demo/article/view/1](https://rapyd.dev/demo/article/view/1)


---
### Edit
Edit is a "form component" usually binded to a model with:  

- "buttons" and "actions" (undo, save, etc..)
- form "fields"
- automatic errors massages / rules management


```html
    <x-rpd::edit title="Article Edit">

       <x-rpd::input model="article.title" label="Title" />
       <x-rpd::rich-text model="article.body" label="Body" />

    </x-rpd::edit>
```

props
- `title`: the heading title for this crud

content/slots
- form fields binded with public/model properties

example: [rapyd.dev/demo/article/edit/1](https://rapyd.dev/demo/article/edit/1)


---


### Fields 

inside some widget views you can drastically semplify the syntax using 
predefined blade components that interacts with livewire

```html
<x-rpd::input model="search" debounce="350" placeholder="search..." />
```

```html
<x-rpd::select model="author_id" lazy :options="$authors" />
```

```html
<!-- tom select dropdown -->
<x-rpd::select-list model="roles" multiple :options="$available_roles" label="Roles" />
or
<x-rpd::select-list model="roles" multiple endpoint="/ajax/roles" label="Roles" />
```

```html
<!-- date, datetime and date-range components -->
<x-rpd::date-time model="date_time" format="dd/MM/yyyy HH:mm:ss" value-format="yyyy-MM-dd HH:mm:ss" label="DateTime" />

<x-rpd::date model="date" format="dd/MM/yyyy" value-format="yyyy-MM-dd" label="Date" />

<x-rpd::date-range
    model_from="date_from"
    model_to="date_to"
    range-separator="-"
    start-placeholder="from"
    end-placeholder="to"
    type="daterange"
    format="dd/MM/yyyy"
    value-format="yyyy-MM-dd"
/>
```

```html
<x-rpd::textarea model="body" label="Body" rows="5" :help="__('the article summary')"/>
```

```html
<!-- quill wysiwyg editor -->
<x-rpd::rich-text model="body" label="Body" />
```


props

- `label`: label to display above the input
- `placeholder`: placeholder to use for the empty first option
- `model`: Livewire model property key
- `options`: array of options e.g. (used in selects)
- `debounce`: Livewire time in ms to bind data on keyup
- `lazy`: Livewire bind data only on change
- `prepend`: addon to display before input, can be used via named slot
- `append`: addon to display after input, can be used via named slot
- `help`: helper label to display under the input
- `icon`: Font Awesome icon to show before input e.g. `cog`, `envelope`
- `size`: Bootstrap input size e.g. `sm`, `lg`
- `rows`: rows nums
- `multiple`: allow multiple option selection (used in select-list)
- `endpoint`: a remote url for fetch optioms (used in select-list)
- `format`: the client-side field format (used in date and date-time)
- `value-format`: the server-side field value format (used in date and date-time)


## special tags

```html
<!-- sort ascending/descending link actions (in a datatable view context)-->
<x-rpd::sort model="id" label="id" />
```
## navigation

Nav Tabs: bootstrap nav-link menu with self-determined active link

```html
<ul class="nav nav-tabs">
    <x-rpd::nav-link label="Home" route="home" />
    <x-rpd::nav-link label="Articles" route="articles" />
    <x-rpd::nav-link label="Article Detail" route="articles.view" :params="1"/>
    <x-rpd::nav-link label="Article edit" route="articles.edit" />
</ul>
```

Nav Items: boostrap vertical menu items / single or grouped (collapsed)

```html
<x-rpd::nav-dropdown icon="fas fa-fw fa-book" label="KnowledgeBase" active="/kb">
    <x-rpd::nav-link label="Edit Categories" route="kb.admin.categories.table" type="collapse-item" />
    <x-rpd::nav-link label="Edit Articles" route="kb.admin.articles.table" type="collapse-item" />
</x-rpd::nav-dropdown>
```


Nav Sidebar: bootstrap sidebar with self-determined or segment-based active link
```html
<x-rpd::sidebar title="Rapyd.dev" class="p-3 text-white border-end">
   <x-rpd::nav-item label="Demo" route="demo" active="/rapyd-demo" />
   <x-rpd::nav-item label="Page" route="page"  />
</x-rpd::sidebar>
```





## Credits

- [Felice Ostuni](https://github.com/zofe)
- [All Contributors](../../contributors)


Inspirations:

- [rapyd-laravel](https://github.com/zofe/rapyd-laravel) my old laravel library (150k downloads)
- [livewire](https://livewire.laravel.com/)  widely used "full-stack framework" to compose laravel application by widgets
- [laravel-bootstrap-components](https://github.com/bastinald/laravel-bootstrap-components) smart library which reduced the complexity of this one



## License & Contacts

Rapyd is licensed under the [MIT license](http://opensource.org/licenses/MIT)

Please join me and review my work on [Linkedin](https://www.linkedin.com/in/feliceostuni/)

thanks




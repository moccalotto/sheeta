<template>
    <div>
        <h1 class="title">Dump</h1>

        <h2 class="subtitle">Query</h2>
        <pre>{{ $route.query }}</pre>
        <br>
        <br>

        <h2 class="subtitle">Params</h2>
        <pre>{{ $route.params }}</pre>
        <br>
        <br>
    </div>
</template>

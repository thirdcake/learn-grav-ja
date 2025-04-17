'use strict';
// 目次を作る
function create_mokuji() {
    const ul = document.querySelector('#mokuji ul');
    if(!ul) {
        return;
    }
    const heads = document.querySelectorAll(
        '.learn-grav-default h2, .learn-grav-default h3'
    );
    const frag = [...heads].reduce((frag, hx) => {
        const text = hx.textContent;
        const link = `#${hx.id}`;
        const a = document.createElement('a');
        a.textContent = text;
        a.href = link;
        const li = document.createElement('li');
        li.appendChild(a);
        li.classList.add('list-group-item');
        if(hx.tagName === 'H3') {
            li.classList.add('ms-3');
        }
        frag.appendChild(li);
        return frag;
    }, document.createDocumentFragment());
    ul.appendChild(frag);
}

// 不足しているtable classを補う
function add_table_class () {
    document.querySelectorAll('.learn-grav-default table').forEach(table => {
        const div = document.createElement('div');
        div.classList.add('table-responsive');
        table.classList.add('table');
        const parent = table.parentNode;
        parent.insertBefore(div, table);
        div.appendChild(table);
    });
}

// 不足しているblockquote classを補う
function add_blockquote_class () {
    const data = [
        {needle: 'Note', csscolor: 'blue'},
        {needle: 'Info', csscolor: 'orange'},
        {needle: 'Tip', csscolor: 'green'},
        {needle: 'Warning', csscolor: 'red'},
        {needle: '訳注', csscolor: 'pink'},
    ];
    document.querySelectorAll('.learn-grav-default blockquote').forEach(blockq => {
        const p = blockq.querySelectorAll('p')[0];
        if(!p) {return;}
        data.forEach(({needle, csscolor})=>{
            if(p.textContent.startsWith(`[!${needle}]`)) {
                blockq.style.borderLeftColor = `var(--bs-${csscolor})`;
            }
        });
    });
}

window.addEventListener('DOMContentLoaded', ()=>{
    create_mokuji();
    add_table_class();
    add_blockquote_class();
}, false);

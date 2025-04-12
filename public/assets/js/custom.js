'use strict';
// 目次を作る
function create_mokuji() {
    const ul = document.querySelector('#mokuji ul');
    if(!ul) {
        return;
    }
    const heads = document.querySelectorAll(
        '.learn-grav-default h2, .learn-grav-default h3, .learn-grav-default h4, .learn-grav-default h5, .learn-grav-default h6'
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
    document.querySelectorAll('.learn-grav-default blockquote').forEach(blockq => {
        const p = blockq.querySelectorAll('p')[0];
        if(!p) {return;}
        if(p.textContent.startsWith('[!Note]')) {
            blockq.style.borderLeftColor = 'var(--bs-blue);';
        } else if(p.textContent.startsWith('[!Info]')) {
            blockq.style.borderLeftColor = 'var(--bs-orange)';
        } else if(p.textContent.startsWith('[!Tip]')) {
            blockq.style.borderLeftColor = 'var(--bs-green)';
        } else if(p.textContent.startsWith('[!Warning]')) {
            blockq.style.borderLeftColor = 'var(--bs-red)';
        } else if(p.textContent.startsWith('[!訳注]')) {
            blockq.style.borderLeftColor = 'var(--bs-pink)';
        }
    });
}

window.addEventListener('DOMContentLoaded', ()=>{
    create_mokuji();
    add_table_class();
    add_blockquote_class();
}, false);
'use strict';
// 目次を作る
function create_mokuji() {
    const ul = document.querySelector('#mokuji ul');
    const template = document.querySelector('template#list-item');
    if(!ul) {
        return;
    }
    const heads = document.querySelectorAll(
        '.learn-grav-default h2, .learn-grav-default h3'
    );
    if(heads.length === 0) {
        return;
    } else {
        mokuji.style.display = 'block';
    }
    const frag = [...heads].reduce((frag, hx) => {
        const clone = template.content.cloneNode(true);
        const a = clone.querySelector('a');
        a.textContent = hx.textContent;
        a.href = `#${hx.id}`;
        if(hx.tagName === 'H3') {
            a.classList.add('ms-3');
        }
        frag.appendChild(clone);
        return frag;
    }, document.createDocumentFragment());
    ul.appendChild(frag);
}

// 不足しているtable classを補う
function add_table_class () {
    document.querySelectorAll('.learn-grav-default table').forEach(table => {
        table.classList.add('table');
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

// 見出しに翻訳元への外部リンクを追加
function headings_external_link () {
    const heads = [...document.querySelectorAll(
        '.learn-grav-default h2, .learn-grav-default h3'
    )];
    heads.forEach(heading => {
        heading.classList.add('heading-external-link');
        const anc = document.createElement('a');
        anc.target = '_blank';
        anc.rel = 'noopener';
        const href = new URL(document.getElementById('source-page').href);
        href.hash = heading.id;
        anc.href = href;
        const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
        svg.setAttribute("width", "24");
        svg.setAttribute("height", "24");
        svg.setAttribute("viewBox", "0 0 24 24");
        const use = document.createElementNS("http://www.w3.org/2000/svg", "use");
        use.setAttributeNS("http://www.w3.org/1999/xlink", "xlink:href", "#box-arrow-up-right");
        const title = document.createElementNS("http://www.w3.org/2000/svg", "title");
        title.textContent = '翻訳元の見出しへのリンク';
        svg.appendChild(use);
        svg.appendChild(title);
        anc.appendChild(svg);
        heading.appendChild(anc);
    });
}

document.addEventListener('DOMContentLoaded', ()=>{
    create_mokuji();
    add_table_class();
    add_blockquote_class();
    headings_external_link();
}, false);

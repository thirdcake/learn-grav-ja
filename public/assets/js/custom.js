'use strict';

const basepath = '/grav-docs-ja';
/**
*  ツリー構造のオブジェクトをpathのlistにする
*/
function root2list(obj, path='') {
    const current = path ? `${ path }/${ obj.name }` : obj.name;
    const result = [current];
    obj.children.forEach((child)=>{
        root2list(child, current).forEach((c)=>{
            result.push(c);
        });
    });
    return result;
}
function createPrevNextLink(list, path) {
    const next = document.getElementById('next');
    const prev = document.getElementById('prev');
    const index = list.indexOf(path);
    if(index-1 < 0) {
        prev.href = '#';
        prev.classList.add('disabled');
        prev.classList.add('border-0');
    } else {
        prev.href = list[index-1];
    }
    if(list.length <= index+1) {
        next.href = '#';
        next.classList.add('disabled');
    } else {
        next.href = list[index+1];
    }
}

function createSidebarMenu(tree, chapterPath) {
    const sidebar = document.getElementById('sidebar-menu');
    const menu = document.createElement('div');
    const createUl = (tree)=>{
        if(tree.children.length===0) {
            return document.createDocumentFragment();
        }
        const ul = document.createElement('ul');
        tree.children.forEach(element => {
            const li = document.createElement('li');
            const a = document.createElement('a');
            a.textContent = element.title;
            a.href = '#';
            li.appendChild(a);
            if(element.name === chapterPath) {
                li.appendChild(createUl(element));
            }
            ul.appendChild(li);
        });
        return ul;
    }
    menu.appendChild(createUl(tree));
    sidebar.appendChild(menu);
}

function createBreadcrumb(tree, pathArray) {
    const bread = document.getElementById('breadcrumb');
    const template = bread.querySelector('template');
    const frag = document.createDocumentFragment();
    let parent = tree;
    let href = tree.name + '/';
    const nextChild = (path, parent) => parent.children.filter(x=>x.name===path)[0];
    for(let i=1; i<pathArray.length-1; i++) {
        const clone = template.content.cloneNode(true);
        const a = clone.querySelector('a');
        parent = nextChild(pathArray[i], parent);
        a.href = href+parent.name+'/';
        a.textContent = parent.title;
        frag.appendChild(clone);
    }
    bread.insertBefore(frag, bread.firstChild);
}

function createMokuji() {
}

window.addEventListener('DOMContentLoaded', ()=>{
    const normalizePath = (path) => path.replace(/\/index\.html$/, '/');
    const currentPath = normalizePath(location.pathname);
    const currentPathArray = currentPath.split('/').filter(x=>x!=='');
    // ナビゲーション関連(list.jsonで作成が必要なもの）
    const jsonpath = new URL(basepath + '/assets/js/list.json', location);
    fetch(jsonpath)
    .then(response => response.json())
    .then(root => {
        root.name = basepath;
        const list = root2list(root, '').map(x=>x+'/');
        console.log(root);
        createPrevNextLink(list, currentPath);
        createSidebarMenu(root, currentPathArray[1]);
        createBreadcrumb(root, currentPathArray);
    })
    .catch((err)=>{console.error(err)});
    // その他
    createMokuji();
}, false);


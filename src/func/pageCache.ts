
// sort()のためにページを比較する関数
const cmpPages = (a: any, b: any ): number => {
    const aarr = (a.url??'').split('/');
    const barr = (b.url??'').split('/');
    const cnt = Math.min(aarr.length, barr.length);
    for(let i = 0; i < cnt; i++) {
        if(aarr[i] > barr[i]) {
        return 1;
        } else if(aarr[i] < barr[i]) {
        return -1;
        }
    }
    return (aarr.length - barr.length);
};

// ドキュメントページ全体を取得する
const pages = Object.values(import.meta.glob('../pages/**/index.md', { eager: true })).sort(cmpPages);


export default pages;
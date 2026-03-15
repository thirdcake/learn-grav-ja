function setTheme(colorMode) {
    let theme;
    if (colorMode === 'system') {
        theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    } else {
        theme = colorMode;
    }
    document.documentElement.setAttribute('data-bs-theme', theme);
}

function initColorMode() {
    const allowedModes = ['light', 'dark', 'system'];
    const savedMode = localStorage.getItem('user-theme');
    const colorMode = (savedMode && allowedModes.includes(savedMode))
        ? savedMode : 'system';
    const inputs = document.getElementsByName('color-mode');

    // 1. 初期状態の適用
    setTheme(colorMode);

    // 2. 保存された値に合わせてラジオボタンのチェック状態を更新
    inputs.forEach(input => {
        // idの末尾（light/dark/system）と一致するか確認
        if (input.id === `color-mode-${colorMode}`) {
            input.checked = true;
        }

        // 3. イベントリスナーの設定
        input.addEventListener('change', (e) => {
            const selectedMode = e.target.id.replace('color-mode-', '');
            if(selectedMode && allowedModes.includes(selectedMode)) {
                localStorage.setItem('user-theme', selectedMode);
                setTheme(selectedMode);
            }else{
                console.error('color mode invalid');
            }
        });
    });

    // 4. システム設定変更への追従（前述の通り）
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
        if (localStorage.getItem('user-theme') === 'system') {
            setTheme('system');
        }
    });
}

initColorMode();



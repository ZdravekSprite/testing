# Git

## laravel -> main

```bash
git add .
git commit -am "laravel signs 2022 05 10"
git push
git checkout main
git pull
git merge laravel
git push
```

## main -> laravel

```bash
git checkout laravel
git pull
git merge main
git push
```

## lotto -> main

```bash
git add .
git commit -am "lotto 2021 12 25"
git push
git checkout main
git pull
git merge lotto
git push
```

## main -> lotto

```bash
git checkout lotto
git pull
git merge main
git push
```

## main -> new

```bash
git branch new
git checkout new
git add .
git commit -am "new"
git push --set-upstream origin new
```

If you know you want to use git reset, it still depends what you mean by "uncommit". If all you want to do is undo the act of committing, leaving everything else intact, use:

git reset --soft HEAD^
If you want to undo the act of committing and everything you'd staged, but leave the work tree (your files) intact:

git reset HEAD^
And if you actually want to completely undo it, throwing away all uncommitted changes, resetting everything to the previous commit (as the original question asked):

git reset --hard HEAD^

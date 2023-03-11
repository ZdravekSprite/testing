# Git

## main -> new

```bash
git branch new
git checkout new
# git checkout -b new
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

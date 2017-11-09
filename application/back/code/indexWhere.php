
        ## 判断是否具有%field%条件
        $filter_%field% = input('filter_%field%', '');
        if ('' !== $filter_%field%) {
            $builder->where('%field%', 'like', '%'. $filter_%field%.'%');
            $filter['filter_%field%'] = $filter_%field%;
        }

����
�����̃��C�u�����́H
--------------------------------------------------------------------------
���o�C���T�C�g�i�K���P�[XHTML�j�ɑΉ����邽�߂̃��C�u�����Q�ł��B

����
���ˑ��֌W�́H
--------------------------------------------------------------------------
PEAR���C�u�����́uNet_UserAgent_Mobile�v�Ɉˑ����Ă��܂��B
> $ pear install Net_UserAgent_Mobile

HTTPResponse�Ƃ������C�u�����Ƒg�ݍ��킹��ƁA�֗��ɂȂ邩������܂���B

�y1�zMobile�N���X
�i�g�ѓd�b�́j�f�o�C�X�����擾���܂��B
PEAR���C�u�����́uNet_UserAgent_Mobile�v�����b�s���O���Ă��܂��B

- PHP5.2�n�ɓ������āA�ÓI�ȋ@�\�Ăяo���ŏ���肪�����܂��B
- docomo�ŗL�̌_������擾����@�\���������Ă��܂��B

> <?php if (Mobile::isDocomo()) : ?>
>     ���Ȃ���docomo���[�U�ł��B
>     <?php if (!Mobile::isPakehoContract()) : ?>
>         �߹ΰ�޲�I�̌_�񂪊m�F�ł��Ȃ��̂ŁA���z�Ɂc�c�B
>     <?php endif ?>
> <?php endif ?>


�y2�zMobile_XHTML�N���X
�g�ѓd�b��XHTML�Ή��ɕK�v�ȁADOCTYPE�錾�̏o�͂ȂǂɑΉ����܂��B

�y3�zMobile_Emoji�N���X
3�L�����A�̊G�����o�͂ɑΉ����܂��B
�G������docomo�G��������ɁA���̊G�����ԍ��Ŏw�肵�܂��B
�o�͂̓L�����A���Ƃ̃o�C�i���i�V�t�gJIS�����j�ŏo�͂��܂��B
������docomo�̊G������<SPAN>�^�O�ŐF�t���������肵�܂��B

�ϊ��̂��߂̃}�[�J�[�쐬�ƁA�o�C�i���ɕϊ�����@�\�ɕ�����Ă��܂��B

> <?php echo Mobile_Emoji::toBinary(Mobile_Emoji::create(1)) ?>

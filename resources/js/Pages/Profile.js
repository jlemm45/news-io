import React from 'react';
import Authenticated from '@/Layouts/Authenticated';
import { Head, useForm } from '@inertiajs/inertia-react';
import Button from '@/Components/Button';
import Input from '@/Components/Input';
import Label from '@/Components/Label';

export default function Profile(props) {
  const { data, setData, post, processing, progress, errors, reset } =
    useForm();

  const submit = e => {
    e.preventDefault();

    post(route('password.confirm'));
  };

  const onHandleChange = event => {
    setData(event.target.name, event.target.value);
  };

  return (
    <Authenticated>
      <Head title="Profile" />

      <form onSubmit={submit}>
        <div className="mt-4">
          <Label forInput="password" value="Password" />

          <Input
            type="password"
            name="password"
            value={data.password}
            className="mt-1 block w-full"
            isFocused={true}
            handleChange={onHandleChange}
          />
        </div>

        <Input
          type="file"
          value={data.avatar}
          handleChange={e => setData('avatar', e.target.files[0])}
        />

        {progress && (
          <progress value={progress.percentage} max="100">
            {progress.percentage}%
          </progress>
        )}

        <div className="flex items-center justify-end mt-4">
          <Button className="ml-4" processing={processing}>
            Save
          </Button>
        </div>
      </form>
    </Authenticated>
  );
}
